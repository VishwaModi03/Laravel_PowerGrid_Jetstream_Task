<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Helpers\PowerGridThemes;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use App\Enums\UserStatus;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
// use App\Livewire\Number;

// use PowerComponents\LivewirePowerGrid\Number;

// PowerGrid core
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Responsive;  


// PowerGrid rules (aliased to avoid collision)
use PowerComponents\LivewirePowerGrid\Facades\Rule as PgRule;
use PowerComponents\LivewirePowerGrid\Rules\RuleActions;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use App\Models\Role;

final class UsersTable extends PowerGridComponent
{
    use WithFileUploads,WithExport;
    
    public string $tableName = 'users-table-dj7lw6-table';

    public bool $deferLoading = true;
   
    public bool $confirmingDelete = false;

    public bool $showViewModal=false;

    public ?User $viewUser=null;
    public ?int $confirmingDeleteId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $profile_photo;
    public ?int $role_id = null;
    public string $status='y';
    public bool $showFilters=true;

    public bool $showErrorBag = true;
  

    #[Computed(persist: true)]
    public function roles()
    {
        return Role::pluck('name', 'id')->toArray();
    }
    
    public function boot():void
    {
        config(['livewire-powergrid.filter'=>'outside']);
    }
    
    protected function queryString(){
        return $this->powerGridQueryString();
    }

    public function noDataLabel(): string|View
    {
      
        return view('users.no-data', ['tableName' => $this->tableName])->render();
    }

    public function setUp(): array
    {
        $this->showCheckBox();
      

        $this->persist(['columns', 'filters'], prefix: auth()->id() ?? '');

        return [
            PowerGrid::exportable('export')
                ->striped()
                ->type(Exportable::TYPE_XLS,Exportable::TYPE_CSV),

            PowerGrid::header()
               
                ->includeViewOnTop('components.users-crud-modals')
                ->showSearchInput()
                ->withoutLoading()
                
                ,
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),

            
            PowerGrid::responsive(),

           

        ];
    }

    public function datasource(): Builder
    {
       

        $query = User::with('role');
        
        if (isset($this->softDeletes)) {
            if ($this->softDeletes === 'with') {
                $query->withTrashed();
            } elseif ($this->softDeletes === 'only') {
                $query->onlyTrashed();
            }
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
           
            ->add('email')
            ->add('email_verified_at')
            ->add('status', fn ($user) => UserStatus::from($user->status)->label())
            ->add('status', fn ($user) => $user->status === 'y'
            ? '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Active</span>'
            : '<span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Inactive</span>'
        )
    
            ->add('role.name',fn($user)=>$user->role->name ?? '')
    
            ->add('created_at')
            ->add('created_at_formatted', fn ($user) =>
                Carbon::parse($user->created_at)->format('d/m/Y H:i')
            )
    
          
            ->add('profile_photo', fn ($user) =>
                '<img class="w-8 h-8 shrink-0 grow-0 rounded-full" src="' . $user->profile_photo_url . '" alt="' . $user->name . '">'
            )
            ;
    }
    
    public function rowTemplates(): array
{
    return [
        'template-user' => '<div class="flex items-center gap-3">
            <img src="{{ avatar }}" alt="avatar" class="w-8 h-8 rounded-full" />
            <div>
                <div class="font-medium">{{ name }} <span class="ml-2 text-xs text-green-600">{{ verified_icon }}</span></div>
                <div class="text-sm text-gray-500">{{ email }}</div>
                <div class="text-xs text-gray-400">{{ role }} • {{ status }}</div>
            </div>
        </div>',
    ];
}


    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
            //Summarization
            // ->withCount('Count Users', true, true)
            ->sortable(),
            Column::make('Profile','profile_photo'),
           
            Column::make('Name', 'name')->template()->sortable()->searchable()
            ->fixedOnResponsive()
            ,
            
            //Validation
           
            Column::make('Email', 'email')->sortable()->searchable()
           
            ,

            Column::make('Status','status','status')->sortable()->searchable(),
            Column::make('Role','role.name')
            
            ->sortable()->searchable(),
           
            
          
            Column::make('Created at', 'created_at_formatted', 'created_at')->sortable(),

          
            Column::action('Action'),
           
        ];
    }

  
    public function hydrate(): void
    {
        sleep(5); // Simulate slow load to show spinner
    }

    public function rules(): array
    {
        return [
           

            PgRule::rows()
                ->when(fn ($user) => is_null($user->email_verified_at))
                ->setAttribute('class', 'bg-red-100 border-l-4 border-red-500'),

            PgRule::button('delete')
                ->when(fn ($user) => auth()->id() === $user->id)
                ->hide(),

            PgRule::button('edit')
                ->when(fn ($user) => !is_null($user->email_verified_at))
                ->setAttribute('class', 'px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700'),

            PgRule::button('verified')
                ->when(fn ($user) => !is_null($user->email_verified_at))
                ->setAttribute('class', 'px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700'),

            PgRule::button('not-verified')
                ->when(fn ($user) => is_null($user->email_verified_at))
                ->setAttribute('class', 'px-2 py-1 bg-yellow-600 text-white rounded text-xs hover:bg-yellow-700'),
        ];
    }

   
    public function filters(): array
    {
        return [
            
            // Filter::softDeletes(),

            Filter::inputText('name')->placeholder('Name'),
            Filter::inputText('email')->placeholder('Email'),

            Filter::boolean('verified', 'email_verified_at')
            ->label('Verified', 'Not Verified')
            ->builder(function (Builder $query, string $value) {
                // $value will be 'true' or 'false' (string)
                return $value === 'true'
                    ? $query->whereNotNull('email_verified_at')
                    : $query->whereNull('email_verified_at');
            }),


           
            Filter::select('role_id', 'users.role_id')
            ->dataSource(Role::all())
            ->optionLabel('name')
            ->optionValue('id'),

        

        Filter::select('status','users.status')
            ->datasource(collect([
                ['value'=>'y','label'=>'Active'],
                ['value'=>'n','label'=>'Inactive'],
            ]))
            ->optionLabel('label')
            ->optionValue('value'),

           

        // numeric range filter for id (min - max)
        Filter::number('id', 'id')
            ->placeholder('min', 'max'),
        ];
    }

    public function header(): array
    {
        return [
          

            Button::add('create-user')
            ->slot(Blade::render(
                '<a href="' . route('users.create') . '" class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">Create User</a>'
            ))
            ->id('create-user-button'), // It's good practice to add an ID


            Button::add('bulk-delete')
                ->slot('Bulk delete (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)')
                ->class('px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm')
                ->dispatch('bulkDelete.' . $this->tableName, []),
        ];
    }

    public function actions(User $row): array
    {
        // For Soft Deleted rows
        if ($row->trashed()) {
            return [
                Button::add('restore')
                    ->slot('') // No text
                    ->id()
                    ->class('text-green-600 hover:text-green-900')
                    // There is no default restore icon. Using 'default-arrow-up' as a placeholder.
                    // You can add your own icon by publishing PowerGrid views/icons.
                    ->icon('default-arrow-up')
                    ->tooltip('Restore User')
                    ->dispatch('restoreRow.' . $this->tableName, ['rowId' => $row->id]),

                Button::add('force-delete')
                    ->slot('') // No text
                    ->id()
                    ->class('text-red-600 hover:text-red-900')
                    ->icon('default-trash')
                    ->tooltip('Delete Permanently')
                    ->dispatch('forceDeleteRow.' . $this->tableName, ['rowId' => $row->id]),
            ];
        }

        // For regular rows
        return [
           

            Button::add('view')
           
            ->icon('default-eye',[
                'class' => '!text-blue-500',
            ]) 
            ->slot('')
            ->id()
            ->class('btn btn-sm')
            ->tooltip('View User')
            ->dispatch('openView.' . $this->tableName, ['rowId' => $row->id]),
            
       

        Button::add('edit')
        ->slot(Blade::render(
            '<a href="' . route('users.edit', $row) . '" class="text-green-600 hover:text-green-900" title="Edit User">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>'
        ))
        ->id('edit-user-'.$row->id),

            Button::add('delete')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                ')
                ->id()
                ->class('text-red-600 hover:text-red-900')
                ->tooltip('Delete User')
                ->dispatch('confirmDelete.' . $this->tableName, ['rowId' => $row->id]),
        ];
    }


    public function actionRules(): array
    {
        return [
           
            Rule::rows()
                ->when(fn($user) => $user->trashed())
                ->setAttribute('class', 'opacity-70 line-through bg-red-50'),

            Rule::button('edit')
                ->when(fn($user) => $user->trashed())
                ->hide(),
    
            Rule::button('delete')
                ->when(fn($user) => $user->trashed())
                ->hide(),
        ];
    }

    #[On('openView.{tableName}')]
    public function openView(int $rowId): void
    {
        $this->viewUser = User::findOrFail($rowId);
        $this->showViewModal = true;
    }

    #[On('restoreRow.{tableName}')]
    public function restoreRow(int $rowId): void
    {
        $user = User::withTrashed()->find($rowId);
        if ($user && $user->trashed()) {
            $user->restore();
            session()->flash('message', 'User restored successfully.');
        }
    }

    #[On('forceDeleteRow.{tableName}')]
    public function forceDeleteRow(int $rowId): void
    {
        $user = User::withTrashed()->find($rowId);
        if ($user) {
            $user->forceDelete(); 
            session()->flash('message', 'User permanently deleted.');
        }
    }


    #[On('bulkDelete.{tableName}')]
    public function bulkDelete(): void
    {
        $ids = $this->getSelectedIds();

        if (!empty($ids)) {
            User::whereIn('id', $ids)->delete();
            $this->ResetBulk();
            session()->flash('message', 'Selected users deleted successfully.');
        }
    }

    public function ResetBulk()
    {
        $this->checkboxValues = [];
    }

    protected function getSelectedIds(): array
    {
        return collect($this->checkboxValues)
            ->map(fn ($value) => (int) $value)
            ->toArray();
    }



    #[On('confirmDelete.{tableName}')]
    public function confirmDelete(int $rowId): void
    {
        if (Auth::check() && Auth::id() === $rowId) {
            session()->flash('message', 'You cannot delete your own account while logged in.');
            return;
        }
        $this->confirmingDeleteId = $rowId;
        $this->confirmingDelete   = true;
    }

    public function delete(): void
    {
        if ($this->confirmingDeleteId &&
            !(Auth::check() && Auth::id() === $this->confirmingDeleteId)) {
            User::whereKey($this->confirmingDeleteId)->delete();
        }

        $this->confirmingDelete = false;
        $this->confirmingDeleteId = null;
        session()->flash('message', 'User deleted successfully.');
    }

    public function resendVerification(int $userId): void
    {
        $user = User::findOrFail($userId);

        if (is_null($user->email_verified_at) && method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
            session()->flash('message', 'Verification email sent.');
        }
    }

    

}
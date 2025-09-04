<?php

namespace App\Livewire\Roles;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Button;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Responsive;  

class RolesTable extends PowerGridComponent
{
    public string $tableName = 'roles_table';

   
    public bool $confirmingDelete = false;
    public ?int $confirmingDeleteId = null;
    public bool $showViewModal = false;
    public ?Role $viewRole = null;

    public bool $deferLoading=True;

   

    public function hydrate(){
        sleep(10);
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('components.roles-crud-modals')
                ,

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),

            PowerGrid::responsive(),
        ];
    }

    public function datasource(): Builder
    {
        return Role::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('status')
            ->add('status_formatted',fn($role)=>$role->status === 'y' ? 'Active':'Inactive');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable()->searchable(),
            Column::make('Name', 'name')->sortable()->searchable(),
            Column::make('Status','status_formatted','status')->sortable(),
            Column::action('Action', 'id'),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('create-role')
            ->slot(Blade::render(
                '<a href="' . route('roles.create') . '" class="flex items-center gap-1 px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Create Role
                </a>'
            ))
        ];
    }

    public function actions(Role $row): array
    {
        return [
            Button::add('view')
             ->icon('default-eye',[
                'class' => '!text-blue-500',
            ]) 
            ->slot('')
            ->id()
            ->class('btn btn-sm')
            ->tooltip('View Role')
            ->dispatch('openView.' . $this->tableName, ['rowId' => $row->id]),

            Button::add('edit')
                ->slot(Blade::render(
                    '<a href="' . route('roles.edit', $row) . '" class="p-1.5 text-green-600 hover:text-green-800" title="Edit Role">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>'
                )),
    
                Button::add('delete')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>')
                ->class('p-1.5 text-red-600 hover:text-red-800')
                ->tooltip('Delete Role')
                ->dispatch('confirmDelete.' . $this->tableName, ['rowId' => $row->id]),
        ];
    }



    #[On('openView.{tableName}')]
    public function openView(int $rowId): void
    {
        $this->viewRole = Role::findOrFail($rowId);
        $this->showViewModal = true;
    }
    
    #[On('confirmDelete.{tableName}')]
    public function confirmDelete(int $rowId): void
    {
        $this->confirmingDeleteId = $rowId;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        if ($this->confirmingDeleteId) {
            $role = Role::withCount('users')->findOrFail($this->confirmingDeleteId);
            if ($role->users_count > 0) {
                session()->flash('error', 'Cannot delete a role assigned to users');
            } else {
                $role->delete();
                session()->flash('message', 'Role deleted successfully.');
            }
        }
    
        $this->confirmingDelete = false;
        $this->confirmingDeleteId = null;
    }


}
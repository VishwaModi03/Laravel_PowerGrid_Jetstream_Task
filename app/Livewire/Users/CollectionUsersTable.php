<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Filter;



final class CollectionUsersTable extends PowerGridComponent
{
    public string $tableName = 'collection-users-table';

    public bool $deferLoading=True; 

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive(),
        ];
    }

    // ✅ Using Collection instead of Eloquent Builder
    public function datasource(): Collection
    {
        // Pull from DB, convert to collection (or you could hardcode like demo)
        return User::all()->map(function ($user) {
            return [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'created_at'      => $user->created_at,
                'created_at_fmt'  => Carbon::parse($user->created_at)->format('d/m/Y H:i'),
            ];
        });
    }

    public function hydrate(){
        sleep(10);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('created_at')
            ->add('created_at_fmt');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Name', 'name')->searchable()->sortable()->fixedOnResponsive(),
            Column::make('Email', 'email')->searchable()->sortable(),
            Column::make('Created At', 'created_at_fmt', 'created_at')->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')
                ->placeholder('Search Name...')
                ->operators(['contains', 'starts_with', 'ends_with']), // optional operator dropdown
        ];
    }
    
    // public function render()
    // {
    //     return view('livewire-powergrid::table');
    // }
}

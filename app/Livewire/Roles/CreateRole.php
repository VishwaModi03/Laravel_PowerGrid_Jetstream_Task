<?php

namespace App\Livewire\Roles;

use App\Models\Role;
use Livewire\Component;

class CreateRole extends Component
{
    public string $name = '';
    public string $status = 'y';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'unique:roles,name'],
            'status' => ['required', \Illuminate\Validation\Rule::in(['y', 'n'])],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        Role::create($data);

        return redirect()->route('roles.index')->with('message', 'Role created successfully.');
    }

    public function render()
    {
        return view('livewire.roles.create-role')
            ->layout('layouts.app'); // Assuming you use layouts.app
    }
}

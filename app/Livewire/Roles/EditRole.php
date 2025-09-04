<?php

namespace App\Livewire\Roles;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditRole extends Component
{
    public Role $role; // This will be injected by Route Model Binding

    public string $name = '';
    public string $status = 'y';

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->status = $role->status;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('roles', 'name')->ignore($this->role->id)],
            'status' => ['required', \Illuminate\Validation\Rule::in(['y', 'n'])],
        ];
    }

    public function update()
    {
        $data = $this->validate();
        
        $this->role->update($data);

        return redirect()->route('roles.index')->with('message', 'Role updated successfully.');
    }

    public function render()
    {
        return view('livewire.roles.edit-role')
            ->layout('layouts.app');
    }
}

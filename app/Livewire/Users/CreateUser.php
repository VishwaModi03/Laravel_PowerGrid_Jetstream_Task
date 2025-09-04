<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Computed;

class CreateUser extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $profile_photo;
    public ?int $role_id = null;
    public string $status = 'y';

    #[Computed(persist: true)]
    public function roles()
    {
        return Role::pluck('name', 'id')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:3'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'profile_photo'=>['nullable','image','max:2048'],
            'role_id'=>['required','integer',ValidationRule::exists('roles','id')],
            'status'=>['required',ValidationRule::in(['y','n'])],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        $userData = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => $data['status'],
            'role_id'  => $data['role_id'],
        ];

        if ($this->profile_photo) {
            $path = $this->profile_photo->store('profile-photos', 'public');
            $userData['profile_photo_path'] = $path;
        }

        User::create($userData);

        // Redirect to the users list with a success message
        return redirect()->route('users.index')->with('message', 'User created successfully.');
    }
    public function render()
    {
        return view('livewire.users.create-user')->layout('layouts.app');
    }
}

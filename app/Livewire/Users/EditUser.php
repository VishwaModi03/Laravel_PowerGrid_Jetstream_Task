<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUser extends Component
{
    use WithFileUploads;

    public User $user;

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

    public function mount(User $user)
    {
        $this->user = $user;

        // Pre-fill the form fields
        $this->name    = $this->user->name;
        $this->email   = $this->user->email;
        $this->role_id = $this->user->role_id;
        $this->status  = $this->user->status;
    }

    protected function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:3'],
            'email'    => [
                'required',
                'email',
                ValidationRule::unique('users', 'email')->ignore($this->user->id), // Ignore the current user's email
            ],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'profile_photo'=>['nullable','image','max:2048'],
            'role_id'=>['required','integer',ValidationRule::exists('roles','id')],
            'status'=>['required',ValidationRule::in(['y','n'])],
        ];
    }

    public function update()
    {
        $this->validate();

        $this->user->name    = $this->name;
        $this->user->email   = $this->email;
        $this->user->role_id = $this->role_id;
        $this->user->status  = $this->status;

        if (filled($this->password)) {
            $this->user->password = Hash::make($this->password);
        }

        if ($this->profile_photo) {
            if ($this->user->profile_photo_path) {
                Storage::disk('public')->delete($this->user->profile_photo_path);
            }
            $path = $this->profile_photo->store('profile-photos', 'public');
            $this->user->profile_photo_path = $path;
        }

        $this->user->save();

        // Redirect back with a message
        return redirect()->route('users.index')->with('message', 'User updated successfully.');
    }
    public function render()
    {
        return view('livewire.users.edit-user')->layout('layouts.app');
    }
}

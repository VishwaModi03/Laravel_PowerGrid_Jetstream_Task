<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm mb-1">Name</label>
                            <input type="text" wire:model.defer="name" class="w-full rounded border-gray-300" />
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Email</label>
                            <input type="email" wire:model.defer="email" class="w-full rounded border-gray-300" />
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Password</label>
                            <input type="password" wire:model.defer="password" class="w-full rounded border-gray-300" />
                            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Confirm Password</label>
                            <input type="password" wire:model.defer="password_confirmation" class="w-full rounded border-gray-300" />
                        </div>

                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role_id" wire:model="role_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a Role</option>
                                @foreach($this->roles() as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Profile Photo</label>
                            <input type="file" wire:model="profile_photo" class="w-full rounded border-gray-300"/>
                            @error('profile_photo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            @if($profile_photo)
                                <img src="{{$profile_photo->temporaryUrl() }}" class="w-16 h-16 rounded-full mt-2"/>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-soft btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
</div>

<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role: ') }} {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form wire:submit.prevent="update">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model.defer="name" class="mt-1 block w-full rounded border-gray-300" />
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" class="mt-1 block w-full rounded border-gray-300">
                                <option value="y">Active</option>
                                <option value="n">Inactive</option>
                            </select>
                            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-soft btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

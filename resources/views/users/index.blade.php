<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                {{-- This is the crucial line that loads your PowerGrid table --}}
                <livewire:users.users-table />

                <!-- @include('users.users-crud-modals') -->
            </div>
        </div>
    </div>
</x-app-layout>
{{-- In resources/views/livewire/users/custom-users-table.blade.php --}}

<div>
    {{-- This is the crucial part: We include the modals FIRST, within the component's scope --}}
    @include('users.users-crud-modals')

    {{-- Then, we include the default PowerGrid table view --}}
    @include(powerGridTheme('tailwind'))
</div>
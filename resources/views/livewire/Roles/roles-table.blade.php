<div>
    {{-- Do your work, then step back. --}}
    <x-app-layout>
        <div class="p-6">
            <h1 class="text-xl font-bold mb-4">Users Table (Collection Datasource)</h1>

            {{-- Render the Livewire component --}}
            <!-- <livewire:roles-table /> -->
            <livewire:powergrid :table="$this" />
        </div>
    </x-app-layout>
</div>


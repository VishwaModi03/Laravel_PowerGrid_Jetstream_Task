<div>
  {{-- View Role Modal --}}
  @if($showViewModal && $viewRole)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/40" wire:click="$set('showViewModal', false)"></div>
      <div class="relative bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h2 class="text-lg font-semibold mb-4">Role Details</h2>
        <div class="space-y-2 text-sm text-gray-800">
          <div><strong>ID:</strong> {{ $viewRole->id }}</div>
          <div><strong>Name:</strong> {{ $viewRole->name }}</div>
          <div>
            <strong>Status:</strong> {{ $viewRole->status === 'y' ? 'Active' : 'Inactive' }}
          </div>
          <div>
            <strong>Created At:</strong> {{ $viewRole->created_at?->format('d M Y, h:i A') }}</div>
          <div>
            <strong>Updated At:</strong> {{ $viewRole->updated_at?->format('d M Y, h:i A') }}</div>
        </div>
        <div class="mt-6 flex justify-end">
          <button class="px-3 py-1 rounded border" wire:click="$set('showViewModal', false)">Close</button>
        </div>
      </div>
    </div>
  @endif

  {{-- Delete Confirmation Modal --}}
  @if($confirmingDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/40" wire:click="$set('confirmingDelete', false)"></div>
      <div class="relative bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h2 class="text-lg font-semibold">Delete Role?</h2>
        <p class="mt-2 text-sm text-gray-600">Are you sure you wish to delete this role?</p>
        <div class="mt-6 flex justify-end gap-3">
          <button class="px-3 py-1 rounded border" wire:click="$set('confirmingDelete', false)">Cancel</button>
          <button class="px-3 py-1 rounded bg-red-600 text-white" wire:click="delete">Delete</button>
        </div>
      </div>
    </div>
  @endif
</div>

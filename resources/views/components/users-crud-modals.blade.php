<div>
    {{-- View User Modal --}}
    @if($showViewModal && $viewUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/40" wire:click="$set('showViewModal', false)"></div>
            <div class="relative bg-white rounded-2xl p-6 w-full max-w-md shadow-xl overflow-y-auto max-h-[80vh]">
                <h2 class="text-lg font-semibold mb-4">User Details</h2>
                
                <div class="space-y-3 text-sm text-gray-800">
                    <div class="flex items-center gap-3">
                        <img class="w-12 h-12 rounded-full" src="{{ $viewUser->profile_photo_url }}" alt="{{ $viewUser->name }}">
                        <div>
                            <div class="text-lg font-medium">{{ $viewUser->name }}</div>
                            @if($viewUser->email_verified_at)
                                <div class="text-green-600 text-xs">Verified</div>
                            @endif
                        </div>
                    </div>

                    <div><strong>Email:</strong> {{ $viewUser->email }}</div>
                    <div><strong>Status:</strong> {{ $viewUser->status === 'y' ? 'Active' : 'Inactive' }}</div>
                    <div><strong>Role:</strong> {{ $viewUser->role?->name ?? '-' }}</div>
                    <div><strong>Created At:</strong> {{ $viewUser->created_at?->format('d M Y, h:i A') }}</div>
                    <div><strong>Updated At:</strong> {{ $viewUser->updated_at?->format('d M Y, h:i A') }}</div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button class="px-3 py-1 rounded border" wire:click="$set('showViewModal', false)">Close</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirm Modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/40" wire:click="$set('confirmingDelete', false)"></div>
            <div class="relative bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
                <h2 class="text-lg font-semibold">Delete User?</h2>
                <p class="mt-1 text-sm text-gray-600">
                    This action {{ in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses(App\Models\User::class))
                        ? 'will soft-delete the user.' : 'will permanently delete the user.' }}
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button class="px-3 py-1 rounded border" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button class="px-3 py-1 rounded bg-red-600 text-white" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>

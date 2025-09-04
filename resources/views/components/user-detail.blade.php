{{-- resources/views/components/user-detail.blade.php --}}
<div class="p-4 bg-gray-50">
    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $row->email }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Role</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $row->role->name ?? 'N/A' }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Status</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $row->status === 'y' ? 'Active' : 'Inactive' }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Joined On</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($row->created_at)->format('M d, Y') }}</dd>
        </div>
    </dl>
</div>
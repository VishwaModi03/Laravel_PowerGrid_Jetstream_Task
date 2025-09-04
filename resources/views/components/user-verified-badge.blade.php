@props(['verified' => false, 'userId' => 0])

<span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium
    {{ $verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">

    @if ($verified)
        {{-- Verified badge --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
        Verified
    @else
        {{-- Not verified badge --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Not Verified

        {{-- Optional resend action --}}
        <button wire:click="resendVerification({{ $userId }})" class="ml-2 underline hover:no-underline">
            Resend
        </button>
    @endif
</span>

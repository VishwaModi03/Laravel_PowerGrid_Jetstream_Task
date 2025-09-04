@props([
    'options' => [],
    'userId'  => null,
    'selected' => null,
])

<select
    class="border rounded px-2 py-1 text-sm"
    x-data
    @change="$wire.dispatch('role-changed', { userId: {{ $userId }}, role: $event.target.value })"
>
    @foreach($options as $value => $label)
        <option value="{{ $value }}" @selected($selected === $value)>{{ $label }}</option>
    @endforeach
</select>

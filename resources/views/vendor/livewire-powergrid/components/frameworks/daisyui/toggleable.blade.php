<!-- @php
    $value = (int) $row->{data_get($column, 'field')};
    $trueValue = data_get($column, 'toggleable')['default'][0];
    $falseValue = data_get($column, 'toggleable')['default'][1];
@endphp

<div class="flex flex-row justify-center">
    @if ($showToggleable)
        @php
            $params = [
                'id' => data_get($row, $this->realPrimaryKey),
                'isHidden' => !$showToggleable,
                'tableName' => $tableName,
                'field' => data_get($column, 'field'),
                'toggle' => $value,
                'trueValue' => $trueValue,
                'falseValue' => $falseValue,
            ];
        @endphp
        <input
            x-data="pgToggleable(@js($params))"
            type="checkbox"
            class="toggle toggle-sm"
            :checked="toggle"
            x-on:click="save"
        />
    @else
        <div @class([
            'text-xs px-4 w-auto py-1 text-center rounded-md',
            'bg-error text-error-content' => $value === 0,
            'bg-info text-info-content' => $value === 1,
        ])>
            {{ $value === 0 ? $falseValue : $trueValue }}
        </div>
    @endif
</div> -->

{{-- NEW content for resources/views/vendor/livewire-powergrid/components/frameworks/daisyui/toggleable.blade.php --}}
@php
    // Get the raw value ('y', 'n', 1, 0, true, false)
    $fieldValue = data_get($row, data_get($column, 'field'));

    // Convert to a strict boolean for AlpineJS and the checkbox
    $value = in_array($fieldValue, [1, true, 'y'], true);

    // Get the text labels defined in your UsersTable.php (e.g., 'Active', 'Inactive')
    $trueValue = data_get($column, 'toggleable')['default'][0];
    $falseValue = data_get($column, 'toggleable')['default'][1];

    $params = [
        'id' => data_get($row, $this->realPrimaryKey),
        'isHidden' => !$showToggleable,
        'tableName' => $tableName,
        'field' => data_get($column, 'field'),
        'toggle' => $value,
        'trueValue' => $trueValue,
        'falseValue' => $falseValue,
    ];
@endphp

<div class="flex flex-row justify-start items-center">
    @if ($showToggleable)
        <div
            x-data="pgToggleable(@js($params))"
            class="flex items-center"
        >
            <input
                type="checkbox"
                class="toggle toggle-sm"
                :checked="toggle"
                x-on:click="save"
            />
            <span
                class="ml-2 text-sm"
                x-text="toggle ? '{{ $trueValue }}' : '{{ $falseValue }}'"
            ></span>
        </div>
    @else
        {{-- This is the fallback for when the toggle is disabled --}}
        <div @class([
            'text-xs px-4 w-auto py-1 text-center rounded-md',
            'bg-error text-error-content' => $value === false,
            'bg-info text-info-content' => $value === true,
        ])>
            {{ $value === false ? $falseValue : $trueValue }}
        </div>
    @endif
</div>
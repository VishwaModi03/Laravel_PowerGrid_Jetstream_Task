{{-- The updated content for your Tailwind toggleable.blade.php --}}
@php
    // Get the raw value ('y', 'n', 1, 0, true, false)
    $fieldValue = data_get($row, data_get($column, 'field'));

    // Convert to a strict boolean for AlpineJS and the checkbox. This is more reliable.
    $value = in_array($fieldValue, [1, true, 'y'], true);

    // Get the text labels defined in UsersTable.php (e.g., 'Active', 'Inactive')
    $trueValue = data_get($column, 'toggleable')['default'][0];
    $falseValue = data_get($column, 'toggleable')['default'][1];

    $params = [
        'id' => data_get($row, $this->realPrimaryKey),
        'isHidden' => !$showToggleable,
        'tableName' => $tableName,
        'field' => data_get($column, 'field'),
        'toggle' => $value, // Use the new boolean value here
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
            {{-- This is the visual switch component --}}
            <div
                :class="{
                    'relative rounded-full w-8 h-4 transition duration-200 ease-linear': true,
                    'bg-pg-secondary-600 dark:pg-secondary-500': toggle,
                    'bg-pg-primary-200': !toggle
                }"
            >
                <label
                    :class="{
                        'absolute left-0 bg-white border-2 mb-2 w-4 h-4 rounded-full transition transform duration-100 ease-linear cursor-pointer': true,
                        'translate-x-full border-pg-secondary-600': toggle,
                        'translate-x-0 border-pg-primary-200': !toggle
                    }"
                    x-on:click="save"
                ></label>
                <input
                    type="checkbox"
                    class="appearance-none opacity-0 w-full h-full active:outline-none focus:outline-none"
                    x-on:click="save"
                >
            </div>

            {{-- This is the text label --}}
            <span
                class="ml-2 text-sm text-gray-700 dark:text-gray-300"
                x-text="toggle ? '{{ $trueValue }}' : '{{ $falseValue }}'"
            ></span>
        </div>
    @else
        {{-- Fallback display for when the toggle is disabled --}}
        <div @class([
            'text-xs px-4 w-auto py-1 text-center rounded-md',
            'bg-red-200 text-red-800' => $value === false,
            'bg-blue-200 text-blue-800' => $value === true,
        ])>
            {{ $value === false ? $falseValue : $trueValue }}
        </div>
    @endif
</div>
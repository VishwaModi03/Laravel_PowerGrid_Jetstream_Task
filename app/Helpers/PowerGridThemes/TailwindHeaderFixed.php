<?php

namespace App\Helpers\PowerGridThemes;

use PowerComponents\LivewirePowerGrid\Themes\Tailwind;

class TailwindHeaderFixed extends Tailwind
{
    public function table(): array
    {
        return [
            // wrapper div with fixed height + scroll
            'div'   => 'relative max-h-[600px] overflow-y-auto shadow border-b border-gray-200 sm:rounded-lg',
            'table' => 'min-w-full divide-y divide-gray-200',
        ];
    }

    public function thead(): array
    {
        return [
            // make thead row sticky
            'tr' => 'bg-gray-50 sticky top-0 z-10',
            'th' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50',
        ];
    }

    public function tbody(): array
    {
        return [
            'tr' => 'bg-white border-b',
            'td' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900',
        ];
    }
}

<?php

namespace App\Helpers\PowerGridThemes;

use PowerComponents\LivewirePowerGrid\Themes\Components\Table;
use PowerComponents\LivewirePowerGrid\Themes\Tailwind;
use PowerComponents\LivewirePowerGrid\Themes\Theme;

class TailwindStriped extends Tailwind
{
    public function table(): Table
    {
        return Theme::table('min-w-full')
            ->div('relative overflow-y-auto max-h-[520px] rounded-lg border border-pg-primary-200') // <- scroll container
            ->thead('bg-pg-primary-100 sticky top-0 z-20') // <- sticky header row
            ->th('sticky top-0 z-20 bg-pg-primary-100 font-extrabold px-2 pr-4 py-3 text-left text-xs text-pg-primary-700 tracking-wider whitespace-nowrap')
            ->tbody('text-pg-primary-800')
            ->trBody('even:bg-neutral-100 border-b border-pg-primary-100 hover:bg-pg-primary-50')
            ->tdBody('px-3 py-2 whitespace-nowrap');
    }
}
<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectUserRole extends Component
{
    /**
     * Create a new component instance.
     */

    public int $userId;
    public string $selected;
    public array $options;

    public function __construct(int $userId, string $selected, array $options)
    {
        $this->userId  = $userId;
        $this->selected = $selected;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-user-role');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserVerifiedBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct( public bool $verified=false,
    public int $userId=0)
    {
        //
       
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-verified-badge');
    }
}

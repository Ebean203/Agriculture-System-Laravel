<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AgricultureAssets extends Component
{
    public bool $offlineMode;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $offlineMode = true)
    {
        $this->offlineMode = $offlineMode;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.agriculture-assets');
    }
}
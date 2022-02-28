<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    private $page;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($page = ['page_title' => null], $title = null)
    {
        $this->page = $page;
        if ($title) {
            $this->page['page_title'] = $title;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.app', $this->page);
    }
}

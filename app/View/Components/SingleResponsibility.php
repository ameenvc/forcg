<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SingleResponsibility extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $item;
    public $item_users, $item_text, $item_select;
    public function __construct($item, $itemUsers, $itemText, $itemSelect)
    {
        $this->item = $item;
        $this->item_users = $itemUsers;
        $this->item_text = $itemText;
        $this->item_select = $itemSelect;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.single-responsibility');
    }
}

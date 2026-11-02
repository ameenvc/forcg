<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SinglePluralResponsibility extends Component
{
    public $item, $item_text, $item_name, $item_select, $users, $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $itemText, $itemName, $itemSelect, $users, $user)
    {
        $this->item = $item;
        $this->item_text = $itemText;
        $this->item_select = $itemSelect;
        $this->item_name = $itemName;
        $this->users = $users;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.single-plural-responsibility');
    }
}

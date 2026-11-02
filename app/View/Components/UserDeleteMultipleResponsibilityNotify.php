<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserDeleteMultipleResponsibilityNotify extends Component
{
   public $item, $item_text, $item_name, $users, $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $itemText, $itemName, $users, $user)
    {
        $this->item = $item;
        $this->item_text = $itemText;
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
        return view('components.user-delete-multiple-responsibility-notify');
    }
}

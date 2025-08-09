<?php

namespace App\Livewire;

use Livewire\Component;

class AnimeList extends Component
{
    public $list_name;
    public $icon;
    public $title;


    public function mount($list_name, $icon, $title)
    {
        $this->list_name = $list_name;
        $this->icon = $icon;
        $this->title = $title;
    }

    public function render()
    {
        return view('livewire.anime-list');
    }
}

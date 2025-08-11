<?php

namespace App\Livewire;

use Livewire\Component;

class Toasts extends Component
{
    public $message = '';
    public $type = 'info';
    public $show = false;
    public $duration = 3000; // Default duration in milliseconds

    protected $listeners = ['showToast'];

    public function showToast($message, $type = 'info', $duration = 3000)
    {
        $this->message = $message;
        $this->type = in_array($type, ['info', 'success', 'warning', 'error']) ? $type : 'info';
        $this->duration = max(1000, $duration);
        $this->show = true;
    }

    public function hide()
    {
        $this->show = false;
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.toasts');
    }
}
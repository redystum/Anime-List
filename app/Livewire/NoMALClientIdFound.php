<?php

namespace App\Livewire;

use App\Models\Token;
use Livewire\Attributes\On;
use Livewire\Component;

class NoMALClientIdFound extends Component
{
    public $show = false;
    public $clientId = '';

    #[On('show-no-mal-client-id-found')]
    public function showDialog()
    {
        $this->show = true;
    }

    public function closeDialog()
    {
        $this->show = false;
        $this->clientId = '';
    }

    public function saveClientId()
    {
        if (empty($this->clientId)) {
            return;
        }

        Token::saveClientId($this->clientId);
        $this->closeDialog();
    }

    public function render()
    {
        return view('livewire.no-mal-client-id-found');
    }
}
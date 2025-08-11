<?php

namespace App\Livewire;

use App\Models\Token;
use App\Services\MalApiRequest;
use Livewire\Attributes\On;
use Livewire\Component;

class NoMALClientIdFound extends Component
{
    public $show = false;
    public $clientId = '';
    public $error = null;

    #[On('show-no-mal-client-id-found')]
    public function showDialog($error = null)
    {
        $this->show = true;
        $this->clientId = '';
        $this->error = $error;
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

        if (MalApiRequest::testToken($this->clientId)) {
            Token::saveClientId($this->clientId);
            $this->closeDialog();
            $this->error = null;
            return;
        }

        $this->error = 'The provided MAL Client ID is invalid. Please check and try again.';
    }

    public function render()
    {
        return view('livewire.no-mal-client-id-found');
    }
}
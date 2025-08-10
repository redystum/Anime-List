<?php

namespace App\Livewire;

use App\Models\Anime;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteDialog extends Component
{
    public bool $show = false;
    public ?int $animeId = null;
    public string $animeTitle = '';

    #[On('show-delete-dialog')]
    public function showDialog(int $animeId, string $animeTitle)
    {
        $this->animeId = $animeId;
        $this->animeTitle = $animeTitle;
        $this->show = true;
    }

    public function closeDialog()
    {
        $this->show = false;
        $this->animeId = null;
    }

    public function deleteAnime()
    {
        if ($this->animeId) {
            $anime = Anime::find($this->animeId);
            $anime?->delete();
            $this->dispatch("updateAll");
        }
        $this->closeDialog();
    }

    public function render()
    {
        return view('livewire.delete-dialog');
    }
}
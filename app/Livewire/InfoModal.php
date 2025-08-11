<?php

namespace App\Livewire;

use App\Models\Anime;
use App\Services\MalApiRequest;
use Livewire\Attributes\On;
use Livewire\Component;

class InfoModal extends Component
{
    public bool $show = false;
    public ?Anime $anime = null;

    public string $notes = '';
    public float $personalScore = 0.0;

    #[On('show-info-modal')]
    public function showModal(int $animeId)
    {
        $this->anime = Anime::with(['genres', 'studios', 'images'])->find($animeId);
        $this->show = true;
        $this->notes = $this->anime->notes ?? '';
        $this->personalScore = $this->anime->localScore ?? 0.0;
    }

    public function closeModal()
    {
        if ($this->anime) {
            $this->anime->notes = $this->notes;
            $this->anime->localScore = $this->personalScore;
            $this->anime->save();
            if ($this->anime->wasChanged()) {
                $this->dispatch("updateAll");
            }
        }

        $this->show = false;
        $this->anime = null;
    }

    public function render()
    {
        if ($this->anime) {
            foreach ($this->anime->relatedAnimes as $related) {
                $related->alreadyOnList = Anime::where('id', $related->id)->exists();
            }
        }

        return view('livewire.info-modal');
    }

    public function toggleCompletedOnInfo(Anime $anime): void
    {
        $anime->completed = !$anime->completed;
        if ($anime->completed) {
            $anime->watching = false;
        }
        $anime->save();

        $this->anime = $anime->fresh();
        $this->dispatch("updateAll");
    }

    public function toggleWatchingOnInfo(Anime $anime): void
    {
        $anime->watching = !$anime->watching;
        $anime->save();

        $this->anime = $anime->fresh();
        $this->dispatch("updateAll");
    }

    public function toggleFavoriteOnInfo(Anime $anime): void
    {
        $anime->favorite = !$anime->favorite;
        $anime->save();

        $this->anime = $anime->fresh();
        $this->dispatch("updateAll");
    }

    public function addToList(int $animeId)
    {
        $response = MalApiRequest::getAnime($animeId);
        if (isset($response['error'])) {
            if (isset($response['need_token']) && $response['need_token']) {
                $this->dispatch('show-no-mal-client-id-found', $response['error']);
                $this->closeModal();
            }
            $this->dispatch("showToast", "The anime could not be found.", 'error');
            return;
        }

        $anime = MalApiRequest::responseToAnime($response);
        if ($anime == null) {
            $this->dispatch("showToast", "The anime could not be found.", 'error');
            return;
        }

        $this->dispatch("update".Anime::LIST_WATCH);
        $this->dispatch("showToast","Anime '{$anime->title}' has been added to your watch list.", 'success');
    }

    public function updateAnimeInfo(){
        if ($this->anime) {
            $response = MalApiRequest::getAnime($this->anime->id);
            if (isset($response['error'])) {
                if (isset($response['need_token']) && $response['need_token']) {
                    $this->dispatch('show-no-mal-client-id-found', $response['error']);
                    return;
                }
                $this->dispatch("showToast", "The anime could not be found.", 'error');
                return;
            }

            $updatedAnime = MalApiRequest::responseToAnime($response);
            if ($updatedAnime == null) {
                $this->dispatch("showToast", "The anime could not be found.", 'error');
                return;
            }

            $this->anime = $updatedAnime;
            $this->dispatch("updateAll");
            $this->dispatch("showToast", "Anime information has been updated successfully.", 'success');
        }
    }
}
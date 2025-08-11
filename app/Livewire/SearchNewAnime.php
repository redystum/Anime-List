<?php

namespace App\Livewire;

use App\Models\Anime;
use App\Services\MalApiRequest;
use Livewire\Attributes\On;
use Livewire\Component;

class SearchNewAnime extends Component
{
    public bool $show = false;
    public string $query = '';

    public $animes = [];

    #[On('show-search-modal')]
    public function showModal(string $query)
    {
        $this->query = $query;
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->query = '';
    }


    public function render()
    {
        $animes = MalApiRequest::searchAnime($this->query);

        if (isset($animes['error'])) {
            $this->animes = null;
            if (isset($animes['need_token']) && $animes['need_token']) {
                $this->dispatch('show-no-mal-client-id-found', $animes['error']);
                $this->closeModal();
            }
            return view('livewire.search-new-anime', ['error' => $animes['message']]);
        }

        $data = $animes['data'] ?? [];
        $animes = collect($data)->map(function ($item) {
            $item = array_merge($item, $item['node']);
            unset($item['node']);
            $item['alreadyOnList'] = Anime::where('id', $item['id'])->exists();
            return $item;
        });

        $this->animes = $animes;

        return view('livewire.search-new-anime');
    }

    public function addToList($animeId)
    {
        $response = MalApiRequest::getAnime($animeId);
        if (isset($response['error'])) {
            if (isset($response['need_token']) && $response['need_token']) {
                $this->dispatch('show-no-mal-client-id-found', $response['error']);
                $this->closeModal();
            }
            dd($response);
            // todo show toast error 404
            return;
        }

        $this->closeModal();

        $anime = MalApiRequest::responseToAnime($response);
        if ($anime == null) {
            // todo show toast error
            dd($response);
            return;
        }

        $this->dispatch("update".Anime::LIST_WATCH);
        // TODO: show toast success
    }
}

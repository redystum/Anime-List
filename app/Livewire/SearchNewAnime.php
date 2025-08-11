<?php

namespace App\Livewire;

use App\Models\Anime;
use App\Models\Token;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
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
        $animes = $this->searchAnime($this->query);

        if (isset($animes['error'])) {
            $this->animes = null;
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

    private function searchAnime(string $animeName)
    {
        if (empty($animeName)) {
            return [];
        }

        $token = config('app.api_keys.mal_client_id');

        if (empty($token)) {
            $token = Token::getClientId();
            if (empty($token)) {
                $this->dispatch('show-no-mal-client-id-found');
                $this->closeModal();
                return ['error' => 'No MAL Client ID found', 'message' => 'Please set your MAL Client ID in the application configuration.'];
            }
        }

        try {
            $response = Http::withHeaders([
                'X-MAL-CLIENT-ID' => $token,
            ])->get('https://api.myanimelist.net/v2/anime', [
                'q' => $animeName,
                'limit' => 15,
            ]);
        } catch (ConnectionException $e) {
            return ['error' => 'Connection error', 'message' => $e->getMessage()];
        }

        if ($response->successful()) {
            return $response->json();
        }

        return ['error' => $response->status(), 'message' => $response->body()];
    }

    public function addToList()
    {
        // TODO
    }
}

<?php

namespace App\Livewire;

use App\Models\Anime;
use Livewire\Component;

class AnimeList extends Component
{
    public $list_name;
    public $icon;
    public $title;
    public $haveAdd = false;


    protected function getListeners()
    {
        return [
            'updateAll' => '$refresh',
            "update{$this->list_name}" => '$refresh',
        ];
    }

    public $sort = 'recently_added';

    public function mount($list_name, $icon, $title, $haveAdd = false)
    {
        $this->list_name = $list_name;
        $this->icon = $icon;
        $this->title = $title;
        $this->haveAdd = $haveAdd;
    }

    public function render()
    {
        $filter_list_status = match ($this->list_name) {
            Anime::LIST_WATCHING => 'watching',
            Anime::LIST_WATCHED => 'completed',
            Anime::LIST_FAVORITE => 'favorite',
            default => null,
        };

        if ($filter_list_status)
            $animes = Anime::where($filter_list_status, true);
        else
            $animes = Anime::where("completed", false);

        $animes->orderBy(...$this->sortToQuery($this->sort));

        $animes = $animes->get();

        return view('livewire.anime-list', compact('animes'));
    }

    public function toggleCompleted(Anime $anime): void
    {
        $anime->completed = !$anime->completed;
        $anime->watching = false;
        $anime->save();
        $this->dispatch("updateAll");
    }

    public function toggleWatching(Anime $anime): void
    {
        $anime->watching = !$anime->watching;
        $anime->save();
        $this->dispatch("update".Anime::LIST_WATCHING);
    }

    public function toggleFavorite(Anime $anime): void
    {
        $anime->favorite = !$anime->favorite;
        $anime->save();
        $this->dispatch("update".Anime::LIST_FAVORITE);
    }

    public function destroy(Anime $anime): void
    {
        $anime->delete();
        $this->dispatch("updateAll");
    }

    private function sortToQuery($sort): array
    {
        return match ($sort) {
            'oldest_added' => ['created_at', 'asc'],
            'recent' => ['start_date', 'desc'],
            'oldest' => ['start_date', 'asc'],
            'az' => ['title', 'asc'],
            'za' => ['title', 'desc'],
            'highest_rated' => ['score', 'desc'],
            'lowest_rated' => ['score', 'asc'],
            'more_episodes' => ['num_episodes', 'desc'],
            'less_episodes' => ['num_episodes', 'asc'],
            'most_favorite' => ['localScore', 'desc'],
            'least_favorite' => ['localScore', 'asc'],
            default => ['created_at', 'desc'],
        };
    }
}

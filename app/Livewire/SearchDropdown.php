<?php

namespace App\Livewire;

use App\Models\Anime;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $query = '';
    public $results = [];
    public $results_pages = [];
    public $selectedIndex = -1;

    public function resetInput()
    {
        $this->query = '';
        $this->results = [];
        $this->results_pages = [];
        $this->selectedIndex = -1;
    }

    public function closeDropdown()
    {
        $this->results = [];
        $this->results_pages = [];
        $this->selectedIndex = -1;
    }

    public function openDropdown()
    {
        $this->updateResults();
        $this->selectedIndex = -1;
    }

    public function updatedQuery()
    {
        $this->updateResults();
        $this->selectedIndex = -1;
    }

    public function updateResults()
    {
        if (strlen($this->query) < 1) {
            $this->results = [];
            return;
        }

        $pages = [
            ['name' => 'Watching now', 'url' => "#" . Anime::LIST_WATCHING, 'icon' => 'fa-eye'],
            ['name' => 'To Watch', 'url' => "#" . Anime::LIST_WATCH, 'icon' => 'fa-tv'],
            ['name' => 'Already Watched', 'url' => "#" . Anime::LIST_WATCHED, 'icon' => 'fa-check'],
            ['name' => 'Favorites', 'url' => "#" . Anime::LIST_FAVORITE, 'icon' => 'fa-heart'],
        ];

        $query = strtolower($this->query);
        $filteredPages = collect($pages)->filter(function ($page) use ($query) {
            return str_contains(strtolower($page['name']), $query);
        })->values();

        $animes = Anime::whereLike('title', "%{$this->query}%")
            ->orWhereLike('title_en', "%{$this->query}%")
            ->orWhereLike('title_jp', "%{$this->query}%")
            ->limit(5)
            ->get();

        $mappedPages = $filteredPages->map(function ($page) {
            return (object)[
                'name' => $page['name'],
                'url' => $page['url'],
                'icon' => $page['icon'],
            ];
        });

        $this->results = $animes;
        $this->results_pages = $mappedPages;
    }

    // Navigate to previous item
    public function incrementIndex()
    {
        $totalResults = count($this->results) + count($this->results_pages) + 1; // +1 -> add anime
        if ($totalResults > 0) {
            $this->selectedIndex = $this->selectedIndex === $totalResults - 1 ? 0 : $this->selectedIndex + 1;
        }
    }

    // Navigate to next item
    public function decrementIndex()
    {
        $totalResults = count($this->results) + count($this->results_pages) + 1; // +1 -> add anime

        if ($totalResults > 0) {
            $this->selectedIndex = $this->selectedIndex === 0 || $this->selectedIndex === -1 ? $totalResults - 1 : $this->selectedIndex - 1;
        }
    }

    public function selectItem(int $index = null)
    {
        if (count($this->results) == 0 && count($this->results_pages) == 0) {
            $this->addAnime();
            return;
        }

        $targetIndex = $index !== null ? $index : $this->selectedIndex;

        if ($targetIndex > count($this->results) + count($this->results_pages) - 1) {
            $this->addAnime();
            return;
        }

        if ($targetIndex > count($this->results) - 1) {
            $pageIndex = $targetIndex - count($this->results);
            $this->selectPage($pageIndex);
            return;
        }

        $targetIndex = $this->clamp($targetIndex, 0, count($this->results) - 1);

        $this->dispatch("scroll-to", '#anime-'.$this->results[$targetIndex]->id, true);
        $this->closeDropdown();
    }

    public function selectPage(int $index = null)
    {
        $targetIndex = $index !== null ? $index : $this->selectedIndex - count($this->results);

        $targetIndex = $this->clamp($targetIndex, 0, count($this->results_pages) - 1);

        $this->dispatch("scroll-to", $this->results_pages[$targetIndex]->url);
        $this->closeDropdown();
    }

    public function addAnime()
    {
        $query = trim($this->query);

        if (strlen($query) < 1) {
            return;
        }

        if (str_starts_with($query, 'id:')) {
            $id = substr($query, 3);
            dd($query, $id);
            // TODO
            $this->resetInput();
            $this->closeDropdown();
            return;
        }

        $this->dispatch('show-search-modal', $query);
        $this->resetInput();
        $this->closeDropdown();
    }

    public function resetSelection()
    {
        $this->selectedIndex = -1;
    }

    public function render()
    {
        return view('livewire.search-dropdown');
    }

    private function clamp($val, $min, $max)
    {
        return max($min, min($max, $val));
    }
}
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
            ->limit(10)
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
        $totalResults = count($this->results) + count($this->results_pages);
        if ($totalResults > 0) {
            $this->selectedIndex = $this->selectedIndex === $totalResults - 1 ? 0 : $this->selectedIndex + 1;
        }
    }

    // Navigate to next item
    public function decrementIndex()
    {
        $totalResults = count($this->results) + count($this->results_pages);

        if ($totalResults > 0) {
            $this->selectedIndex = $this->selectedIndex === 0 || $this->selectedIndex === -1 ? $totalResults - 1 : $this->selectedIndex - 1;
        }
    }

    public function selectItem(int $index = null)
    {
        if ($index == null) {
            $index = $this->selectedIndex;
        }

        if ($index > count($this->results) - 1) {
            $index -= count($this->results);
            $this->selectPage($index);
            return;
        }

        $index = $this->clamp($index, 0, count($this->results) - 1);

//        $this->dispatch("show-info-modal", $this->results[$index]->id);
        $this->dispatch("scroll-to", '#anime-'.$this->results[$index]->id, true);
        $this->closeDropdown();
    }

    public function selectPage(int $index = null)
    {
        if ($index == null) {
            $index = $this->selectedIndex - count($this->results);
        }

        $index = $this->clamp($index, 0, count($this->results_pages) - 1);

        $this->dispatch("scroll-to", $this->results_pages[$index]->url);
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
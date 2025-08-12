<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;

class OptionsOffcanvas extends Component
{
    public bool $pinNav = false;
    public bool $autoUpdate = true;
    public bool $markAiring = true;
    public bool $showStats = true;
    public bool $open = false;

    #[On('openOptions')]
    public function toggleOffcanvas()
    {
        $this->open = !$this->open;
    }

    public function closeOffcanvas()
    {
        $this->open = false;
    }

    public function mount()
    {
        $this->pinNav = session('pinNav', true);
        $this->autoUpdate = session('autoUpdate', false);
        $this->markAiring = session('markAiring', false);
        $this->showStats = session('showStats', false);
    }

    public function updatedPinNav($value)
    {
        session(['pinNav' => $value]);
        $this->dispatch('pinNavChanged', $value);
    }

    public function updatedAutoUpdate($value)
    {
        session(['autoUpdate' => $value]);
    }

    public function updatedMarkAiring($value)
    {
        session(['markAiring' => $value]);
        $this->dispatch('updateAll');
    }

    public function updatedShowStats($value)
    {
        session(['showStats' => $value]);
        $this->dispatch('updateAll');
    }

    public function exportDatabase()
    {

    }

    public function importDatabase()
    {

    }

    public function confirmDelete()
    {

    }

    public function deleteDatabase()
    {
        $this->dispatch('reloadPage');
    }

    public function handleFileImport($fileContent)
    {

    }

    public function render()
    {
        return view('livewire.options-offcanvas');
    }
}
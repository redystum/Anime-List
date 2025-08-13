<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;


class OptionsOffcanvas extends Component
{
    use WithFileUploads;

    public bool $pinNav = false;
    public bool $autoUpdate = true;
    public bool $markAiring = true;
    public bool $showStats = true;
    public bool $open = false;
    public bool $showDeleteConfirmation = false;

    public $importFile;


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
        $zip = new ZipArchive();
        $fileName = 'anime_list_export_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $fileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $tables = $this->getNonLaravelTables();

            foreach ($tables as $table) {
                $csvContent = $this->exportTableToCsv($table);
                $zip->addFromString($table . '.csv', $csvContent);
            }

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        session()->flash('error', 'Failed to create export file.');
        return redirect()->back();
    }


    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteDatabase()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $tables = $this->getNonLaravelTables();

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->showDeleteConfirmation = false;
        session()->flash('success', 'Database cleared successfully.');
        $this->dispatch('reloadPage');
    }

    private function getNonLaravelTables()
    {
        $allTables = DB::select('SHOW TABLES');
        $ignoreTables = [
            'migrations', 'failed_jobs', 'cache', 'cache_locks', 'jobs', 'job_batches',
            'token'
        ];

        return collect($allTables)->map(function ($table) {
            return array_values((array)$table)[0];
        })->reject(function ($table) use ($ignoreTables) {
            return in_array($table, $ignoreTables);
        })->toArray();
    }

    private function exportTableToCsv($tableName)
    {
        $data = DB::table($tableName)->get();

        if ($data->isEmpty()) {
            return '';
        }

        $csv = Writer::createFromString('');

        // header
        $csv->insertOne(array_keys((array)$data->first()));

        // data
        foreach ($data as $row) {
            $csv->insertOne(array_values((array)$row));
        }

        return $csv->toString();
    }


    public function importDatabase()
    {
        $this->dispatch('trigger-file-input');
    }

    public function updatedImportFile()
    {
        if (!$this->importFile) {
            $this->dispatch("showToast", "No file selected.", 'error');
            return;
        }

        $this->dispatch("showToast", "Processing...", 'info');

        $zipPath = $this->importFile->storeAs('temp', 'import_' . time() . '.zip');
        $fullZipPath = Storage::path($zipPath);
        $extractPath = storage_path('app/temp/import_' . time());

        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($fullZipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            try {
            DB::transaction(function () use ($extractPath) {

                // Disable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                $importOrder = [
                    'genres',
                    'related_animes',
                    'studios',
                    'animes',
                    'images',
                    'anime_genres',
                    'anime_related_animes',
                    'anime_studios',
                ];

                $importTable = function ($filePath, $tableName) {
                    if (!Schema::hasTable($tableName)) {
                        return;
                    }

                    $csv = Reader::createFromPath($filePath, 'r');
                    $csv->setHeaderOffset(0);
                    $records = $csv->getRecords();

                    foreach ($records as $record) {
                        if (str_starts_with($tableName, 'anime_')) {
                            DB::table($tableName)->insertOrIgnore($record);
                        } else {
                            DB::table($tableName)->updateOrInsert(['id' => $record['id']], $record);
                        }
                    }
                };

                foreach ($importOrder as $tableName) {
                    $filePath = "$extractPath/$tableName.csv";
                    if (file_exists($filePath)) {
                        $importTable($filePath, $tableName);
                    }
                }

                // Import any remaining CSV files not in the order list
                $csvFiles = File::files($extractPath);
                foreach ($csvFiles as $file) {
                    $tableName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    if (!in_array($tableName, $importOrder)) {
                        $importTable($file->getPathname(), $tableName);
                    }
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });
            } catch (\Exception $e) {
                $this->dispatch("showToast", "Failed to import database", 'error');
                File::deleteDirectory($extractPath);
                Storage::delete($zipPath);
                \Log::error('Database import error: ' . $e->getMessage());
                return;
            }

            File::deleteDirectory($extractPath);
            Storage::delete($zipPath);

            $this->dispatch('updateAll');
            $this->dispatch("showToast", "Database imported successfully.", 'success');
        } else {
            $this->dispatch("showToast", "Failed to import database.", 'error');
        }
    }


    public function render()
    {
        return view('livewire.options-offcanvas');
    }
}
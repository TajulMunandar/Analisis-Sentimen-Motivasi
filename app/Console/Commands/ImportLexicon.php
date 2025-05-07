<?php

namespace App\Console\Commands;

use App\Models\Lexicon;
use Illuminate\Console\Command;

class ImportLexicon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lexicon:import {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import lexicon data from a TSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $handle = fopen($filePath, "r");
        if (!$handle) {
            $this->error("Cannot open file: $filePath");
            return;
        }

        // Skip header
        fgetcsv($handle, 0, "\t");

        while (($data = fgetcsv($handle, 0, "\t")) !== false) {
            Lexicon::updateOrCreate(
                ['word' => $data[0]],
                ['polarity' => $data[1]]
            );
        }

        fclose($handle);
        $this->info("Lexicon data imported successfully!");
    }
}

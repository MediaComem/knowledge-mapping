<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Synchronization;
use App\Utils\Status;
use Illuminate\Support\Facades\Http;

class RunSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zotero:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Zotero synchronization script';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $lastRun = Synchronization::latest()->first();
        if ($lastRun && $lastRun->status === Status::RUNNING->value) {
            $this->error("<fg=yellow>Sync already in progress...</>");
            return;
        }
        $response = Http::retry(3, 100)->get(config('flask.app_address') . '/run');
        if ($response->ok()) {
            $scriptRun = new Synchronization();
            $scriptRun->status = Status::RUNNING->value;
            $scriptRun->save();
            $this->info("Script started successfully (it may take a few minutes to complete)");
        } else {
            $this->error("An error occured while reaching the Flask server.");
        }
    }
}

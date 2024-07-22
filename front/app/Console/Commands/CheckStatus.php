<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Synchronization;
use App\Utils\Status;
use App\Services\SyncService;

class CheckStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zotero:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check zotero synchronization status';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $syncService = new SyncService();
        $status = $syncService->getStatus();
        $color = match ($status) {
            Status::COMPLETED->value => 'green',
            Status::ERROR->value => 'red',
            default => 'yellow',
        };
        $this->line("Script status: <fg={$color};options=bold>{$status}</>");
    }
}

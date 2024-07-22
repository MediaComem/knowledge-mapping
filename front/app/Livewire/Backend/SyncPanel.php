<?php

namespace App\Livewire\Backend;

use App\Models\Synchronization;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use App\Utils\Status;
use App\Services\SyncService;

class SyncPanel extends Component
{
    public $lastRunDatetime;
    public $lastRunStatus;
    private SyncService $syncService;

    // __construct is used instead of mount because of bug (otherwise syncService is not initialized in mount method)
    public function __construct()
    {
        $this->syncService = new SyncService();
        $this->lastRunDatetime = $this->syncService->getLastRunDatetime();
        $this->lastRunStatus = $this->syncService->getStatus();
        if ($this->lastRunStatus === Status::RUNNING->value) {
            $this->dispatch('sync-started');
        }
    }

    #[On('scriptRan')]
    public function runScript()
    {
        $this->syncService->runScript();
        $this->lastRunDatetime = $this->syncService->getLastRunDatetime();
        $this->lastRunStatus = Status::RUNNING->value;
        $this->dispatch('sync-started');
    }

    public function updateLastRunStatus()
    {
        if (
            $this->lastRunStatus === Status::RUNNING->value &&
            $this->syncService->getStatus() !== Status::RUNNING->value
        ) {
            $this->dispatch('sync-ended');
            $this->lastRunStatus = $this->syncService->getStatus();
            $this->lastRunDatetime = $this->syncService->getLastRunDatetime();
        }
    }

    public function render()
    {
        return view('livewire.backend.sync-panel', [
            'lastRunDatetime' => $this->lastRunDatetime,
            'lastRunStatus' => $this->lastRunStatus,
            'Status' => Status::class,
        ]);
    }
}

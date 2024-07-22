<?php

namespace App\Services;

use App\Utils\Status;
use Illuminate\Support\Facades\Http;
use App\Models\Synchronization;

class SyncService
{
    public $status = Status::NEVER_RAN->value;

    public function __construct()
    {
        $lastRunStatus = Synchronization::latest()->first();
        $this->status = $lastRunStatus ? $lastRunStatus->status : Status::NEVER_RAN->value;
        $this->getStatus();
    }

    function runScript(): bool
    {
        $response = Http::retry(3, 100)->get(config('flask.app_address') . '/run');
        if ($response->ok()) {
            $scriptRun = new Synchronization();
            $scriptRun->status = Status::RUNNING->value;
            $scriptRun->save();
            $this->saveStatus($scriptRun->status);
            return true;
        } else {
            $this->saveStatus(Status::ERROR->value);
            return false;
        }
    }

    function getStatus()
    {
        if ($this->status === Status::NEVER_RAN->value || $this->status === Status::RUNNING->value) {
            // In case of error, the request will be retried 3 times with a 100ms delay between each retry
            $response = Http::retry(3, 100)->get(config('flask.app_address') . '/status');
            $jsonData = $response->json();
            if ($response->failed()) {
                $this->saveStatus(Status::ERROR->value);
            } elseif ($jsonData['status'] !== $this->status && $jsonData['status'] !== Status::NEVER_RAN->value) {
                $this->saveStatus($jsonData['status']);
            }
        }

        return $this->status;
    }

    function getLastRunDatetime()
    {
        $scriptRun = Synchronization::latest()->first();
        if ($scriptRun) {
            return $scriptRun->updated_at;
        }
        return null;
    }

    private function saveStatus($status)
    {
        $scriptRun = Synchronization::latest()->first();
        if (!$scriptRun) {
            return;
        }
        $scriptRun->status = $status;
        $scriptRun->save();
        $this->status = $scriptRun->status;
    }
}

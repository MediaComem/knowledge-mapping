<?php

namespace App\Livewire\Component;

use Livewire\Component;
use Livewire\Attributes\On;

class RunTaskProcessButton extends Component
{
    public $scriptIsRunning;

    public function render()
    {
        return view('livewire.component.run-task-process-button', [
            'scriptIsRunning' => $this->scriptIsRunning,
        ]);
    }

    #[On('sync-started')]
    public function lockButton()
    {
        $this->scriptIsRunning = true;
    }

    #[On('sync-ended')]
    public function unlockButton()
    {
        $this->scriptIsRunning = false;
    }


    public function runScript()
    {
        $this->dispatch('scriptRan');
    }
}

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div @if($lastRunStatus===$Status::RUNNING->value)wire:poll="updateLastRunStatus"@endif class="px-12 py-8 bg-white border-b border-gray-200">
        <h1 class="text-2xl mb-2">Dataset synchronization</h1>
        <div class="flex mt-4 justify-between items-end">
            <div>
                <h4 class="text-sm mb-2 uppercase font-bold">Last update</h4>
                <h4>Status: <span class="text-lg {{$lastRunStatus === $Status::COMPLETED->value ? "text-green-600" : ($lastRunStatus === $Status::RUNNING->value ? "text-yellow-600" : "text-red-600")}} font-bold">{{ $lastRunStatus }}</span></h4>
                @if($lastRunStatus !== $Status::NEVER_RAN->value)
                    <h4 class="text-lg">Date: {{ $lastRunDatetime }}</h4>
                @endif
            </div>
            <div>
                <livewire:component.run-task-process-button :scriptIsRunning="$lastRunStatus===$Status::RUNNING->value"/>
            </div>
        </div>
    </div>
</div>

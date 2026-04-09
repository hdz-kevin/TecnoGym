<?php

namespace App\Livewire\Actions;

use App\Actions\SyncStatuses;
use Livewire\Component;

class SyncStatusesAction extends Component
{
    public bool $syncing = false;

    /**
     * Run the status sync and emit a success notification.
     */
    public function sync(): void
    {
        $this->syncing = true;

        $result = (new SyncStatuses())->handle();

        $this->syncing = false;

        $this->dispatch('statuses-synced', count: $result);
    }

    public function render()
    {
        return view('livewire.actions.sync-statuses-action');
    }
}

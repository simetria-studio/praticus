<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Note;

class RecoveryNoteCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $note;

    /**
     * Create a new event instance.
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
    }
}

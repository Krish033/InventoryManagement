<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LogForDeletedEvents {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $primaryKey;

    /**
     * Create a new event instance.
     */
    public function __construct(public $logRecordDeleted, public $table) {
        $this->primaryKey = Arr::first(DB::getSchemaBuilder()->getColumnListing($table));
        // dd($this->primaryKey);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
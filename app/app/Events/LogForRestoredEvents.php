<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class LogForRestoredEvents {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $primaryKey;

    /**
     * Create a new event instance.
     */
    public function __construct(public $logRecordRestore, public $table, public $ipaddress = '::1') {
        $this->primaryKey = DB::getSchemaBuilder()->getColumnListing($table)[0];
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
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

class LogForUpdatedEvents {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $primaryKey;
    public $updatingItem;

    /**
     * Create a new event instance.
     */
    public function __construct(public $updatedData, public $table, public $id, public $ipaddress = '::1') {
        $this->primaryKey = DB::getSchemaBuilder()->getColumnListing($table)[0];
        $this->updatingItem = (array) DB::table($table)->where($this->primaryKey, '=', $id)->get()[0];
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
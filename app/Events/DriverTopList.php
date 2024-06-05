<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverTopList implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

     public $drivers;
    public function __construct($drivers)
    {
        $this->drivers = $drivers->map(function ($driver) {
            return [
                'name' => $driver->name,
                'phone' => $driver->phone
            ];
        });
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');

        return new Channel('driver-list-channel');
    }

    public function broadcastAs()
    {
        return 'driver-list-event';
    }
}

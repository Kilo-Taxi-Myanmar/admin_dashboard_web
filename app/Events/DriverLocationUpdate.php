<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public  $drivers;
    public function __construct($drivers)
    {
        $this->drivers = $drivers->map(function ($driver) {
            return [
                'id'=>$driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'lat' => $driver->lat,
                'lng' => $driver->lng,
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
        return new Channel('driver-location-channel');
    }

    public function broadcastAs()
    {
        return 'driver-location-event';
    }
}

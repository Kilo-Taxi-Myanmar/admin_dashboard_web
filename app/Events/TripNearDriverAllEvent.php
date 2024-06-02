<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripNearDriverAllEvent implements ShouldBroadcast

{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $nearbyDrivers ;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($nearbyDrivers)
    {
        $this->nearbyDrivers = "hello world";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('request-near-driver-all-channel');
    }

    public function broadcastAs()
    {
        return 'request-near-driver-all-event';
    }


    
}

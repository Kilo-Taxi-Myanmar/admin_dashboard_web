<?php

namespace App\Listeners;

use App\Events\TripNearDriverAllEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;
class newNearDriver implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
  
    /**
     * Handle the event.
     *
     * @param  \App\Events\TripNearDriverAllEvent  $event
     * @return void
     */
    public function handle(TripNearDriverAllEvent $event)
    {
        $nearbyDrivers = $event->nearbyDrivers;
        
        // You can customize the data format as per your requirement
        $data = [
            'nearby_drivers' => $nearbyDrivers->toArray()
        ];

        // Serialize the data
        $jsonData = json_encode($data);

        // Send the event data to Redis
        // Redis::publish('nearby-drivers', $jsonData);
        // broadcast(new TripNearDriverAllEvent($jsonData));
    }
}


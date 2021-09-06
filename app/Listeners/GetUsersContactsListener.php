<?php

namespace App\Listeners;

use App\Events\SendNotifecation;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetUsersContactsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user=$event->user;
      $myLocations=UserLocation::where('user_id','=',$user->id)->where('created_at','>', now()->subSecond(14*60))->get();
      $locations=UserLocation::where('user_id','!=',$user->id)->where('created_at','>', now()->subSecond(14*60))->get();
   $GLOBALS['users']=[];
    $myLocations->each(function ($myLocation) use($locations){
                $locations->each(function ($location) use ($myLocation){
                  $distance=$this->vincentyGreatCircleDistance((float)$myLocation->latitude,(float)$myLocation->longitude,(float)$location->latitude,(float)$location->longitude);

                  if ($distance<=1)
                      $GLOBALS['users'][]=$location->user_id;

              });
     });
    broadcast(new SendNotifecation($GLOBALS['users']));




    }
    public  function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
//        dd([
//            'x'=>$latitudeFrom,
//            'y'=>$longitudeFrom,
//            'x1'=>$latitudeTo,
//            'y1'=>$longitudeTo
//        ]);
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

}

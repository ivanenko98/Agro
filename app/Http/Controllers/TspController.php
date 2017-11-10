<?php

namespace App\Http\Controllers;

use App\Elevator;
use App\Shipment;
use App\Tool;
use Illuminate\Http\Request;

class TspController extends Controller
{
    private $locations 	= array();		// all locations to visit
    private $longitudes = array();
    private $latitudes 	= array();
    private $shortest_route = array();	// holds the shortest route
    private $shortest_routes = array();	// any matching shortest routes
    private $shortest_distance = 0;		// holds the shortest distance
    private $all_routes = array();		// array of all the possible combinations and there distances


    // add a location
    public function add($name,$longitude,$latitude){
        $this->locations[$name] = array('longitude'=>$longitude,'latitude'=>$latitude);
    }

    // the main function that des the calculations
    public function compute(){

        $locations = $this->locations;

        foreach ($locations as $location=>$coords){
            $this->longitudes[$location] = $coords['longitude'];
            $this->latitudes[$location] = $coords['latitude'];
        }
        $locations = array_keys($locations);

        $this->all_routes = $this->array_permutations($locations);

        foreach ($this->all_routes as $key=>$perms){
            $i=0;
            $total = 0;
            foreach ($perms as $value){
                if ($i<count($this->locations)-1){
                    $total+=$this->distance($this->latitudes[$perms[$i]],$this->longitudes[$perms[$i]],$this->latitudes[$perms[$i+1]],$this->longitudes[$perms[$i+1]]);
                }
                $i++;
            }
            $this->all_routes[$key]['distance'] = $total;
            if ($total<$this->shortest_distance || $this->shortest_distance ==0){
                $this->shortest_distance = $total;
                $this->shortest_route = $perms;
                $this->shortest_routes = array();
            }
            if ($total == $this->shortest_distance){
                $this->shortest_routes[] = $perms;
            }
        }
    }

    // work out the distance between 2 longitude and latitude pairs
    public function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return round($angle * $earthRadius);
    }

    // work out all the possible different permutations of an array of data
    private function array_permutations($items, $perms = array( )) {

        static $all_permutations;

        if (empty($items)) {
            $all_permutations[] = $perms;
        }  else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $this->array_permutations($newitems, $newperms);
            }
        }
        return $all_permutations;
    }

    // return an array of the shortest possible route
    public function shortest_route(){
        return $this->shortest_route;
    }

    // returns an array of any routes that are exactly the same distance as the shortest (ie the shortest backwards normally)
    public function matching_shortest_routes(){
        return $this->shortest_routes;
    }

    // the shortest possible distance to travel
    public function shortest_distance(){
        return $this->shortest_distance;
    }

    // returns an array of all the possible routes
    public function routes(){
        return $this->all_routes;
    }

    public function index(Request $request){

        $shipment = Shipment::find($request->shipment);

        $points['shipment'] = [
            'name' => $shipment->name,
            'latitude' => $shipment->latitude,
            'longitude' => $shipment->longitude
        ];

        foreach($request->tools as $tool){

            $tools = Tool::where('name', $tool)->get();

            foreach($tools as $tool){
                $points[$tool->factory->name] = [
                    'name' => $tool->factory->name,
                    'latitude' => $tool->factory->latitude,
                    'longitude' => $tool->factory->longitude
                ];
            }
        }

        $elevators = Elevator::all();

        $closestElevator = $this->closestElevator($elevators, $points);

        $points['elevator'] = [
            'name' => $closestElevator->name,
            'latitude' => $closestElevator->latitude,
            'longitude' => $closestElevator->longitude
        ];

        print_r($points);

        $tsp = new TspController;

        foreach($points as $point){
            $tsp->add($point['name'], $point['longitude'], $point['latitude']);
        }

        $tsp->compute();


        echo 'Shortest Distance: '.$tsp->shortest_distance();

        echo '<br />Shortest Route: ';

        print_r($tsp->shortest_route());

        echo '<br />Num Routes: '.count($tsp->routes());

        echo '<br />Matching shortest Routes: ';

        print_r($tsp->matching_shortest_routes());

        echo '<br />All Routes: ';

        print_r($tsp->routes());
    }

    public function closestElevator($elevators, $points){
        foreach ($elevators as $elevator){
            $distance = $this->distance($points['shipment']['latitude'], $points['shipment']['longitude'], $elevator->latitude, $elevator->longitude);
            $elevatorPoint[][$elevator->id] = $distance;
        }

        $elevator = Elevator::find(key(min($elevatorPoint)));
        return $elevator;
    }
}

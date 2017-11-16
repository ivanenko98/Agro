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
    private $all_routes = array();		// array of all the  possible combinations and there distances


    // add a location
//    public function add($name,$longitude,$latitude){
//        $this->locations[$name] = array('longitude'=>$longitude,'latitude'=>$latitude);
//        dd($this->locations);
//        dd($this->locations);
//    }

    // the main function that des the calculations
    public function compute($shipment){

        $locations = $this->locations;
//        dd($locations);
//        array_unshift($perm, $shipment->name);
//        array_pop($locations);
//        $elevator = array_pop($locations);

        foreach ($locations as $location=>$coords){

            $this->longitudes[$location] = $coords['longitude'];
            $this->latitudes[$location] = $coords['latitude'];
        }

        $locations = array_keys($locations);

//        dd($locations);
        $permutations = $this->array_permutations($locations, $perms = array());

//        $shipment = Shipment::find($id_shipment);

        foreach ($permutations as $perm){
//            array_unshift($perm, $shipment->name);
//            array_push($perm, $elevator);

            $this->all_routes[] = $perm;
        }

        print_r($this->all_routes);

        foreach ($this->all_routes as $key=>$perms){
//            dd($this->all_routes);
            $i=0;
            $total = 0;
//            dd($this->latitudes);
            foreach ($perms as $value){
//                dd($perms);
//                dd($this->latitudes[$value]);
                if ($i<count($this->locations)-1){
//                    dd($this->latitudes);
//                    dd($perms[0]);
//                    dd($this->latitudes[$perms[$i]]);
                    $total+=$this->distance($this->latitudes[$perms[$i]],$this->longitudes[$perms[$i]],$this->latitudes[$perms[$i+1]],$this->longitudes[$perms[$i+1]]);
//                    dd(111);
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
    private function array_permutations($items, $perms = array()) {
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

    function cartesian($input) {
        $result = array();

        while (list($key, $values) = each($input)) {
            // If a sub-array is empty, it doesn't affect the cartesian product
            if (empty($values)) {
                continue;
            }

            // Seeding the product array with the values from the first sub-array
            if (empty($result)) {
                foreach($values as $value) {
                    $result[] = array($key => $value);
                }
            }
            else {
                // Second and subsequent input sub-arrays work like this:
                //   1. In each existing array inside $product, add an item with
                //      key == $key and value == first item in input sub-array
                //   2. Then, for each remaining item in current input sub-array,
                //      add a copy of each existing array inside $product with
                //      key == $key and value == first item of input sub-array

                // Store all items to be added to $product here; adding them
                // inside the foreach will result in an infinite loop
                $append = array();

                foreach($result as &$product) {
                    // Do step 1 above. array_shift is not the most efficient, but
                    // it allows us to iterate over the rest of the items with a
                    // simple foreach, making the code short and easy to read.
                    $product[$key] = array_shift($values);

                    // $product is by reference (that's why the key we added above
                    // will appear in the end result), so make a copy of it here
                    $copy = $product;

                    // Do step 2 above.
                    foreach($values as $item) {
                        $copy[$key] = $item;
                        $append[] = $copy;
                    }

                    // Undo the side effecst of array_shift
                    array_unshift($values, $product[$key]);
                }

                // Out of the foreach, we can add to $results now
                $result = array_merge($result, $append);
            }
        }

        return $result;
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


        foreach($request->tools as $tool){

            $tools = Tool::where('name', $tool)->get()->all();


//            dd($tools);

            foreach($tools as $tool){
                $points[$tool->name][] = [
                    'name' => $tool->factory->name,
                    'latitude' => $tool->factory->latitude,
                    'longitude' => $tool->factory->longitude
                ];
            }
        }

        $elevators = Elevator::all();

//        $closestElevator = $this->closestElevator($elevators, $points);

        foreach($elevators as $elevator){
            $points['elevator'][] = [
                'name' => $elevator->name,
                'latitude' => $elevator->latitude,
                'longitude' => $elevator->longitude
            ];
        }

        //Получаем все фабрики на которых есть этот Tool

        print_r($points);

        $result = $this->cartesian($points);

        print_r($result);

        foreach ($result as $item){
            dd($result);
            $item[] = array_map("unserialize", array_unique( array_map("serialize", $item) ));
        }



        dd($item);
//        print_r($final_points_array);

        $tsp = new TspController;

//        foreach ($final_points_array as $fpoints1){
//            $fpoints1[] = [
//                'name' => $shipment->name,
//                'latitude' => $shipment->latitude,
//                'longitude' => $shipment->longitude
//            ];


//            array_unshift($fpoints1, $fpoints1['shipment'] = [
//                'name' => $shipment->name,
//                'latitude' => $shipment->latitude,
//                'longitude' => $shipment->longitude
//            ]);

//            $age = array_merge( [
//                'shipment' => [
//                    'name' => $shipment->name,
//                    'latitude' => $shipment->latitude,
//                    'longitude' => $shipment->longitude
//                ]
//            ], $fpoints1);
//
//            dd($age);

//            array_unshift($fpoints1, [
//                'name' => $shipment->name,
//                'latitude' => $shipment->latitude,
//                'longitude' => $shipment->longitude
//            ]);
//            dd($fpoints1);
//        }



//        $final_points_array = [
//            'name' => $shipment->name,
//            'latitude' => $shipment->latitude,
//            'longitude' => $shipment->longitude
//        ];

//        dd($final_points_array);
        foreach($final_points_array as $fpoints){
            //            dd($fpoints);
            foreach ($fpoints as $fpoint){

                print_r($fpoints);
                //$tsp->add($fpoint['name'], $fpoint['longitude'], $fpoint['latitude']);
                $this->locations[$fpoint['name']] = array('longitude'=>$fpoint['longitude'],'latitude' => $fpoint['latitude']);
            }
        }
//        dd(count($this->locations));
        dd($this->locations);



        $tsp->compute($shipment);
//        dd($this->locations);

        echo 'Shortest Distance: '.$tsp->shortest_distance();

        echo '<br> Shortest Route: ';

        print_r($tsp->shortest_route());

        echo '<br> Num Routes: '.count($tsp->routes());

        echo '<br> Matching shortest Routes: ';

        print_r($tsp->matching_shortest_routes());

        echo '<br>All Routes: ';

        print_r($tsp->routes());
    }

//    public function closestElevator($elevators, $points){
//        foreach ($elevators as $elevator){
//            $distance = $this->distance($points['shipment']['latitude'], $points['shipment']['longitude'], $elevator->latitude, $elevator->longitude);
//            $elevatorPoint[][$elevator->id] = $distance;
//        }
//
//        $elevator = Elevator::find(key(min($elevatorPoint)));
//        return $elevator;
//    }
}

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
    private $possible_route = array();

    // the main function that des the calculations
    public function compute($shipment){

        $locations = $this->locations;

        foreach ($locations as $locations1){
            foreach ($locations1 as $location=>$coords){
                $this->longitudes[$location] = $coords['longitude'];
                $this->latitudes[$location] = $coords['latitude'];
            }

            $locations = array_keys($locations1);

            array_shift($locations);

            foreach ($this->array_permutations($locations) as $permutation) {
                $permutations[] = $permutation;
            }

            foreach ($permutations as $perm){
                array_unshift($perm, $shipment->name);
                $this->all_routes[] = $perm;
                unset($perm);
            }

            unset($permutations);

            foreach ($this->all_routes as $key=>$perms){
                $i=0;
                $total = 0;

                for ($n = 0; $n < count($perms) - 1; $n++){
                    if ($i<count($perms)-1){
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

                unset($perms);
            }
            unset($this->latitudes);
            unset($this->longitudes);
            unset($this->all_routes);
            unset($permutations);
            $this->possible_route[$this->shortest_distance()] = $this->shortest_route();
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

    private function array_permutations(array $elements)
    {
        if (count($elements) <= 1) {
            yield $elements;
        } else {
            foreach ($this->array_permutations(array_slice($elements, 1)) as $permutation) {
                foreach (range(0, count($elements) - 2) as $i) {
                    yield array_merge(
                        array_slice($permutation, 0, $i),
                        [$elements[0]],
                        array_slice($permutation, $i)
                    );
                }
            }
        }
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

    public function index(Request $request){

        $shipment = Shipment::find($request->shipment);

        $points['shipment'][] = [
                'name' => $shipment->name,
                'latitude' => $shipment->latitude,
                'longitude' => $shipment->longitude
        ];

        foreach($request->tools as $tool){

            $tools = Tool::where('name', $tool)->get()->all();

            foreach($tools as $tool){
                $points[$tool->name][] = [
                    'name' => $tool->factory->name,
                    'latitude' => $tool->factory->latitude,
                    'longitude' => $tool->factory->longitude
                ];
            }
        }

        $elevators = Elevator::all();

        foreach($elevators as $elevator){
            $points['elevator'][] = [
                'name' => $elevator->name,
                'latitude' => $elevator->latitude,
                'longitude' => $elevator->longitude
            ];
        }

        $result = $this->cartesian($points);

        foreach ($result as $item){
            $points_array[] = array_map("unserialize", array_unique( array_map("serialize", $item) ));
        }

        $tsp = new TspController;

        foreach($points_array as $key => $points){
            foreach ($points as $point){
                $tsp->add($point['name'], $key, $point['longitude'], $point['latitude']);
            }
        }

        $tsp->compute($shipment);

        echo 'Shortest Distance: '.$tsp->shortest_distance();

        echo '<br> Shortest Route: ';

        print_r($tsp->shortest_route());
    }

    public function add($name, $name_array, $longitude, $latitude)
    {
        $this->locations[$name_array][$name] = array('longitude'=> $longitude,'latitude' => $latitude);
    }

    function change_key($key, $new_key, $arr, $rewrite=true){
        if(!array_key_exists($new_key,$arr) || $rewrite){
            $arr[$new_key]=$arr[$key];
            unset($arr[$key]);
            return true;
        }
        return false;
    }
}

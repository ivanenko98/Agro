<?php

namespace App\Http\Controllers;

use App\Elevator;
use App\Shipment;
use App\Tool;
use Illuminate\Http\Request;

class PathController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Shipment $shipment
     * @return array
     */
    public function index(Request $request, Shipment $shipment)
    {
        $shipment = Shipment::find($request->shipment);

        $points['shipment'] = [
            'latitude' => $shipment->latitude,
            'longitude' => $shipment->longitude
        ];

        foreach($request->tools as $tool){

            $tool = Tool::find($tool);

            $points[$tool->name] = [
                'latitude' => $tool->factory->latitude,
                'longitude' => $tool->factory->longitude
            ];

            $words[] = $tool;
        }

        $elevators = Elevator::all();

        $closestElevator = $this->closestElevator($elevators, $points);

        $points['elevator'] = [
            'latitude' => $closestElevator->latitude,
            'longitude' => $closestElevator->longitude
        ];

//        print_r($points);


//        $distance = $this->haversineGreatCircleDistance($points['shipment']['latitude'], $points['shipment']['longitude'], $points['Tool 1']['latitude'], $points['Tool 1']['longitude']);
        $distance = $this->haversineGreatCircleDistance(49.35, 34.34, 50.00,36.15);

        dd($distance);

        $paths = $this->allPossibleOptions($words);

//        dd($paths);

//        return $paths;
        return $this->optimalPath($points, $paths);
    }

    /**
     * @param $words
     * @return array
     */
    public function allPossibleOptions($words){

        $result = [];

        //количество элементов массива
        $n = count($words);

        //ищем факториал
        $f = 1;
        for ($i = 1; $i <= $n; $i++) $f = $f * $i;
        for($i=0; $i < $f; $i++) {
            $pos = $i % ($n-1);
            if($pos == 0) $first = array_shift($words);
            $result[$i] = [];
            for($j = 0; $j < $n-1; $j++ ) {
                if($j == $pos) $result[$i][]=$first;
                $result[$i][] = $words[$j];
            }
            if($pos==($n-2)) {
                $words[]=$first;
            }
        }
        return ($result);
    }

    function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return round($angle * $earthRadius);
    }

    public function closestElevator($elevators, $points){
        foreach ($elevators as $elevator){
            $distance = $this->haversineGreatCircleDistance($points['shipment']['latitude'], $points['shipment']['longitude'], $elevator->latitude, $elevator->longitude);
            $elevatorPoint[][$elevator->id] = $distance;
        }

        $elevator = Elevator::find(key(min($elevatorPoint)));
        return $elevator;
    }



//    public function optimalPath($points, $paths){
//
////        dd($paths);
//
//        foreach($points as $point){
//
//            foreach($request->tools as $tool) {
//                $tools = Tool::find();
//
//
//                $points[$tool->name] = [
//                    'latitude' => $tool->factory->latitude,
//                    'longitude' => $tool->factory->longitude
//                ];
//
//            }
//        }
//
//        for ($i = 0; $i < count($paths); $i++){
//            $paths[$i]['elevator'] = [
//                'jdshv' => 'jfhwei'
//            ];
//        }
//
//
//        $addPoints = function ($n, $points){
//            return array_push($n, end($points));
//
//        };
//
//        $fullPath = array_map($addPoints, $paths);
//
//        return $paths;
//    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

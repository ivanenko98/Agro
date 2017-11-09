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

//        $words[] = 'shipment';

        foreach($request->tools as $tool){

            $tool = Tool::find($tool);

            $points[$tool->name] = [
                        'latitude' => $tool->factory->latitude,
                        'longitude' => $tool->factory->longitude
            ];

            $words[] = $tool->name;
        }
//        print_r($points);

        $paths = $this->allPossibleOptions($words);

//        for($i = 0; $i < count($paths); $i++){
//             if($paths[0] = '1' and end($paths) != '4'){
//
//             }
//        }
//
//        dd(end($paths));
//
//        foreach ($paths as $path){
//        }

//        $paths

        return $paths;
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

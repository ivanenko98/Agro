<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class OPFEController extends Controller
{
    public function index(){
        $regions = Region::all();

        foreach ($regions as $region){

            foreach ($region->fields as $field){
                $region_fields[$field->name] = [
                    'name' => $field->name,
                    'latitude' => $field->latitude,
                    'longitude' => $field->longitude,
                    'harvest' => $field->harvest
                ];
            }
            foreach ($region->elevators as $elevator){
                $region_elevators[$elevator->name] = [
                    'name' => $elevator->name,
                    'latitude' => $elevator->latitude,
                    'longitude' => $elevator->longitude,
                    'capacity' => $elevator->capacity
                ];

                $tsp = new TSPController;

                foreach ($region_fields as $region_field){
                    $distance = $tsp->distance($elevator->latitude, $elevator->longitude, $region_field['latitude'], $region_field['longitude']);
                    $elevators_distance[$elevator->name][$region_field['name']] = $distance;
                }
                $report = $this->filingElevator($region_elevators, $region_fields, $elevators_distance[$elevator->name], $elevator->name);

                if ($report !== false){
                    unset($region_fields);
                    $region_fields = $report;
                    $rest_fields = $region_fields;
                }else{
                    unset($rest_field);
                    break;
                }
            }

            if (isset($rest_fields)){
                foreach ($rest_fields as $rest_field){
                    $coordinates_fields[$rest_field['name']] = [
                        'latitude' => $rest_field['latitude'],
                        'longitude' => $rest_field['longitude']
                    ];
                }
//                print_r($coordinates_fields);
                $possible_elevator = $this->getCenter($coordinates_fields);
                print_r($possible_elevator);
            }
        }
    }

    public function getCenter($polygon) {
        $NumPoints = count($polygon);

        if($polygon[$NumPoints-1] == $polygon[0]){
            $NumPoints--;
        }else{
            //Add the first point at the end of the array.
            $polygon[$NumPoints] = $polygon[0];
        }

        $x = 0;
        $y = 0;

        $lastPoint = $polygon[$NumPoints - 1];

        for ($i=0; $i<=$NumPoints - 1; $i++) {
            $point = $polygon[$i];
            $x += ($lastPoint[0] + $point[0]) * ($lastPoint[0] * $point[1] - $point[0] * $lastPoint[1]);
            $y += ($lastPoint[1] + $point[1]) * ($lastPoint[0] * $point[1] - $point[0] * $lastPoint[1]);
            $lastPoint = $point;
        }

        $x /= 6*$this->ComputeArea($polygon);
        $y /= 6*$this->ComputeArea($polygon);

        return array($x, $y);
    }

    function ComputeArea($polygon)
    {
        $NumPoints = count($polygon);

        if($polygon[$NumPoints-1] == $polygon[0]){
            $NumPoints--;
        }else{
            //Add the first point at the end of the array.
            $polygon[$NumPoints] = $polygon[0];
        }

        $area = 0;

        for( $i = 0; $i <= $NumPoints; ++$i )
            $area += $polygon[$i][0]*( $polygon[$i+1][1] - $polygon[$i-1][1] );
        $area /= 2;
        return $area;
    }

    public function filingElevator($region_elevators, $region_fields, $elevator_distance, $elevator){

        $capacity_elevator = $region_elevators[$elevator]['capacity'];

        while ($capacity_elevator != 0){
            if (!empty($elevator_distance)){
                $min_distance_point = min($elevator_distance);
                $key_min_distance_point  = array_search($min_distance_point, $elevator_distance);

                if($capacity_elevator >= $region_fields[$key_min_distance_point]['harvest']){
                    $capacity_elevator = $capacity_elevator - $region_fields[$key_min_distance_point]['harvest'];
                    $region_elevators[$elevator]['capacity'] = $capacity_elevator;
                    unset($elevator_distance[$key_min_distance_point]);
                    unset($region_fields[$key_min_distance_point]);
                }else{
                    $region_fields[$key_min_distance_point]['harvest'] = $region_fields[$key_min_distance_point]['harvest'] - $capacity_elevator;
                    unset($region_elevators[$elevator]);
                    $capacity_elevator = 0;
                }
            }else{
                return false;
            }
        }
        return $region_fields;
    }
}

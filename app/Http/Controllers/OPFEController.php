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
                    unset($elevators_distance);
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

                $possible_elevators[$region->name] = $this->GetCenterFromDegrees($coordinates_fields);
            }
            unset($region_fields);
            unset($region_elevators);
            unset($report);
            unset($rest_fields);
            unset($coordinates_fields);
        }
        dd($possible_elevators);
    }

    function GetCenterFromDegrees($data)
    {
        if (!is_array($data)) return FALSE;

        $num_coords = count($data);

        $X = 0.0;
        $Y = 0.0;
        $Z = 0.0;

        foreach ($data as $coord)
        {
            $lat = $coord['latitude'] * pi() / 180;
            $lon = $coord['longitude'] * pi() / 180;

            $a = cos($lat) * cos($lon);
            $b = cos($lat) * sin($lon);
            $c = sin($lat);

            $X += $a;
            $Y += $b;
            $Z += $c;
        }

        $X /= $num_coords;
        $Y /= $num_coords;
        $Z /= $num_coords;

        $lon = atan2($Y, $X);
        $hyp = sqrt($X * $X + $Y * $Y);
        $lat = atan2($Z, $hyp);

        return array($lat * 180 / pi(), $lon * 180 / pi());
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

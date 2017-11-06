<?php

namespace App\Http\Controllers;

use App\Elevator;
use Illuminate\Http\Request;

class ElevatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Elevator $elevator
     * @return \Illuminate\Http\Response
     */
    public function index(Elevator $elevator)
    {
        $elevator = Elevator::all();
        return response()->json($elevator, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $elevator = Elevator::create($request->all());
        return response()->json($elevator, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Elevator $elevator
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Elevator $elevator)
    {
        $elevator = Elevator::find($elevator->id);
        return $elevator;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Elevator $elevator
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Elevator $elevator)
    {
        $elevator->update($request->all());
        return response()->json($elevator, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Elevator $elevator
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Elevator $elevator)
    {
        $elevator->delete();
        return response()->json(null, 204);
    }
}

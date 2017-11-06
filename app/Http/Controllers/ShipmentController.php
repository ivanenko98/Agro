<?php

namespace App\Http\Controllers;

use App\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Shipment $shipment
     * @return \Illuminate\Http\Response
     */
    public function index(Shipment $shipment)
    {
        $shipment = Shipment::all();
        return response()->json($shipment, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Shipment $shipment
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Shipment $shipment)
    {
        $shipment = Shipment::create($request->all());
        return response()->json($shipment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Shipment $shipment
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Shipment $shipment)
    {
        $shipment = Shipment::find($shipment->id);
        return $shipment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Shipment $shipment
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Shipment $shipment)
    {
        $shipment->update($request->all());
        return response()->json($shipment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shipment $shipment
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Farmer $farmer
     * @return \Illuminate\Http\Response
     */

    public function index(Farmer $farmer)
    {
        $farmer = Farmer::all();
        return response()->json($farmer, 201);
    }


    public function store(Request $request, Farmer $farmer)

    {
        $farmer = Farmer::create($request->all());
        return response()->json($farmer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Farmer $farmer
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Farmer $farmer)
    {
        $farmer = Farmer::find($farmer->id);
        return $farmer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Farmer $farmer
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Farmer $farmer)
    {
        $farmer->update($request->all());
        return response()->json($farmer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Farmer $farmer
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Farmer $farmer)
    {
        $farmer->delete();
        return response()->json(null, 204);
    }
}

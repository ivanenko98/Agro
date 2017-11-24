<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $region = Region::all();
        return response()->json($region, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $region = Region::create($request->all());
        return response()->json($region, 201);
    }

    /**
     * Display the specified resource.
     * @param Region $region
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Region $region)
    {
        $region = Region::find($region->id);
        return $region;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Region $region
     * @return \Illuminate\Http\Response
     * @internal param Region $farmer
     * @internal param int $id
     */
    public function update(Request $request, Region $region)
    {
        $region->update($request->all());
        return response()->json($region, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Region $region
     * @return \Illuminate\Http\Response
     * @internal param Region $farmer
     * @internal param int $id
     */
    public function destroy(Region $region)
    {
        $region->delete();
        return response()->json(null, 204);
    }
}

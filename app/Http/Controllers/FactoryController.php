<?php

namespace App\Http\Controllers;

use App\Factory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function index()
    {
        $factory = Factory::all();
        return response()->json($factory, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Factory $farmer
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factory = Factory::create($request->all());
        return response()->json($factory, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Factory $farmer
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Factory $factory)
    {
        $factory = Factory::find($factory->id);
        return $factory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Factory $farmer
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Factory $factory)
    {
        $factory->update($request->all());
        return response()->json($factory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Factory $farmer
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Factory $factory)
    {
        $factory->delete();
        return response()->json(null, 204);

    }
}

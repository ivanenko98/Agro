<?php

namespace App\Http\Controllers;

use App\Rent;
use Illuminate\Http\Request;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Rent $rent
     * @return \Illuminate\Http\Response
     */
    public function index(Rent $rent)
    {
        $rent = Rent::all();
        return response()->json($rent, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Rent $rent
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Rent $rent)
    {
        $rent = Rent::create($request->all());
        return response()->json($rent, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Rent $rent
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Rent $rent)
    {
        $rent = Rent::find($rent->id);
        return $rent;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Rent $rent
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Rent $rent)
    {
        $rent->update($request->all());
        return response()->json($rent, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rent $rent
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Rent $rent)
    {
        $rent->delete();
        return response()->json(null, 204);
    }
}

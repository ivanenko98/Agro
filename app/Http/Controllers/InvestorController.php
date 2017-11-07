<?php

namespace App\Http\Controllers;

use App\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Investor $investor
     * @return \Illuminate\Http\Response
     */
    public function index(Investor $investor)
    {
        $investor = Investor::all();
        return response()->json($investor, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Investor $investor
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Investor $investor)
    {
        $investor = Investor::create($request->all());
        return response()->json($investor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Investor $investor
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Investor $investor)
    {
        $investor = Investor::find($investor->id);
        return $investor;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Investor $investor
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Investor $investor)
    {
        $investor->update($request->all());
        return response()->json($investor, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Investor $investor
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Investor $investor)
    {
        $investor->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        $tool = Tool::all();
        return response()->json($tool, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Tool $farmer
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tool = Tool::create($request->all());
        return response()->json($tool, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Tool $tool
     * @return \Illuminate\Http\Response
     * @internal param Tool $farmer
     * @internal param int $id
     */
    public function show(Tool $tool)
    {
        $tool = Tool::find($tool->id);
        return $tool;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Tool $tool
     * @return \Illuminate\Http\Response
     * @internal param Tool $farmer
     * @internal param int $id
     */
    public function update(Request $request, Tool $tool)
    {
        $tool->update($request->all());
        return response()->json($tool, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tool $tool
     * @return \Illuminate\Http\Response
     * @internal param Tool $farmer
     * @internal param int $id
     */
    public function destroy(Tool $tool)
    {
        $tool->delete();
        return response()->json(null, 204);
    }
}

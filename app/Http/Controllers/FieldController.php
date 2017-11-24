<?php

namespace App\Http\Controllers;

use App\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $field = Field::all();
        return response()->json($field, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $field = Field::create($request->all());
        return response()->json($field, 201);
    }

    /**
     * Display the specified resource.
     * @param Field $field
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Field $field)
    {
        $field = Field::find($field->id);
        return $field;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Field $field
     * @return \Illuminate\Http\Response
     * @internal param Field $farmer
     * @internal param int $id
     */
    public function update(Request $request, Field $field)
    {
        $field->update($request->all());
        return response()->json($field, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Field $field
     * @return \Illuminate\Http\Response
     * @internal param Field $farmer
     * @internal param int $id
     */
    public function destroy(Field $field)
    {
        $field->delete();
        return response()->json(null, 204);
    }
}

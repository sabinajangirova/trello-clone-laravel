<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Column::all()->toArray());
    }

    public function tasks(Column $column){
        return response()->json($column->tasks);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $column = Column::create($request->only('name'));

        return response()->json([
            'status' => (bool)$column,
            'message' => $column ? 'Column Created' : 'Error Creating Column'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Column $column)
    {
        $status = $column->update($request->only('name'));

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Column Updated!' : 'Error Updating Column'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Column $column)
    {
        foreach($column->tasks as $task){
            $task->delete();
        }
        $status = $column->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Column Deleted' : 'Error Deleting Column'
        ]);
    }
}

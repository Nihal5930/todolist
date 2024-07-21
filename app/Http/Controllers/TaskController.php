<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd(isset($_GET));
        $tasks=Task::orderBy('id', 'DESC')->where('completed',0)->where('removed',0)->get();
        // dd($tasks);
        return view('welcome', compact('tasks'));
    }

    public function getAllData(){
        $tasks=Task::orderBy('id', 'DESC')->where('completed',1)->orWhere('removed',0)->get();
        return response()->json([
            'success' => true,
            'task' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->id);
        if($request->id==null){
            $request->validate([
                'title' => 'required|string|max:255|unique:tasks,title',
            ]);
            $task= Task::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'task' => $task,
            ]);
        }
        else{
            $request->validate([
                'title' => 'required|string|max:255|unique:tasks,title',
            ]);
            $data = $request->all();
            $record = Task::findOrFail($request->id);
            $record->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Task Updated successfully.',
                'task' => $record,
            ]);

        }

    }


    public function editTask(Request $request){
        // dd($request);
        $editThisTask = Task::find($request->id);
        return response()->json([
            'task' => $editThisTask
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request){
        // dd($request->id);
        $status = Task::find($request->id);
        $status->completed=$request->status;
        $status->save();
        return response()->json([
            'success' => true,
            'message' => 'Task Completed successfully.',
            'status' => $status,
        ]);
    }

    public function deleteTask(Request $request){
        $status = Task::find($request->id);
        $status->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task Removed successfully.',
            'status' => $status,
        ]);
    }
}

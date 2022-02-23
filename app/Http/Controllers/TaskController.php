<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listTasks = $this->taskService->getTasks();

        return response()->json($listTasks);
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
        request()->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'project_id' => 'required',
                'user_id' => 'required'
            ]
        );

        $createTask = $this->taskService->createNewTask($request);

        return response()->json([$createTask]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);

        return response()->json($task);
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
    public function update(Request $request, $id)
    {
        request()->validate(
            [
                'title' => 'required',
                'status' => 'required|in:' . TASK::STATUS_NOT_STARTED . ',' . TASK::STATUS_IN_PROGRESS . ',' . TASK::STATUS_READY_FOR_TEST . ',' . TASK::STATUS_COMPLETED . ',',
            ]
        );

        $updateTask = $this->taskService->updateTask($request, $id);

        return response()->json(['messages' => $updateTask]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteTask = $this->taskService->deleteTask($id);

        return response()->json(['messages' => $deleteTask]);
    }

    public function assign(Request $request)
    {
        $assignTask = $this->taskService->assignTask($request);

        return response()->json(['messages' => $assignTask]);
    }
}

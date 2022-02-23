<?php

namespace App\Services;

use App\Models\Task;
use App\Models\ProjectMember;

class TaskService
{
    public function getTasks()
    {
        $tasks = Task::get();
    
        return $tasks;
    }

    public function getTaskById($id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return ['messages' => 'Task not found'];
        }

        return $task;
    }

    public function createNewTask($request)
    {
        if ($request->user()->role != 'product_owner') {
            return 'User has no permission for the action.';
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => Task::STATUS_NOT_STARTED,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id
        ];
        $createTask = Task::create($data);
        
        return $createTask;
    }

    public function updateTask($request, $id)
    {
        $user = $request->user();
        
        $task = Task::find($id);
        if (!$task) {
            return 'Task not found';
        }

        if ($user->role == 'member' && $task->user_id != $user->id) {
            if ($request->status != null)
            return 'User has no permission to edit this task status';
        }

        $data = [
            'title' => $request->title ? $request->title : $task->title,
            'description' => $request->description ? $request->description : $task->description,
            'status' => $request->status,
        ];

        $updateTask = $task->update($data);
        
        return 'Successfully update task.';
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return 'Task not found';
        }
        $task->delete($id);

        return 'Successfully deleted task.';
    }

    public function assignTask($request)
    {
        if ($request->user()->role != 'product_owner') {
            return 'User has no permission for the action.';
        }

        $task = Task::find($request->task_id);
        if (!$task) {
            return 'Task not found';
        }

        $isProjectMember = ProjectMember::where('project_id', $task->project_id)->where('user_id', $request->user_id)->first();
        if (!$isProjectMember ) {
            return 'User is not member of the project.';
        }

        $task->user_id = $request->user_id;
        $task->save();

        return 'Successfully assign task.';
    }
}
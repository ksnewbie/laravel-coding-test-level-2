<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectMember;

class ProjectService
{
    public function getProjects()
    {
        $users = Project::get();
    
        return $users;
    }

    public function getProjectById($id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            return ['messages' => 'Project not found'];
        }

        return $project;
    }

    public function createNewProject($request)
    {
        $data = [
            'name' => $request->name,
            'user_id' => $request->user()->id
        ];

        $createProject = Project::create($data);
        
        return $createProject;
    }

    public function updateProject($request, $id)
    {
        $data = [
            'name' => $request->name,
        ];
        
        $project = Project::find($id);
        if (!$project) {
            return 'Project not found';
        }
        $updateProject = $project->update($data);
        
        return 'Successfully update project.';
    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return 'Project not found';
        }
        $project->delete($id);

        return 'Successfully deleted project.';
    }

    public function addMember($request)
    {
        $project = Project::find($request->project_id);
        if (!$project) {
            return 'Project not found';
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return 'User not found';
        }
        $existMember = ProjectMember::where('project_id', $request->project_id)->where('user_id', $request->user_id)->first();
        
        if ($existMember) {
            return 'User already added to the project.';
        }
        ProjectMember::create($request->all());

        return 'Successfully added member to project.';
    }
}
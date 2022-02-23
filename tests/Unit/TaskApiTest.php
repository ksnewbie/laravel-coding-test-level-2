<?php

namespace Tests\Unit;

use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testChangeTaskStatus()
    {
        $user = User::factory()->create(['role' => 'product_owner']);
        $member = User::factory()->create(['role' => 'member']);
        Sanctum::actingAs(
            $user,
            ['manage-projects', 'manage-tasks']
        );

        $data = [
            'name' => 'TestProject',
        ];
        $response = $this->post(route('projects.store'), $data);
        $content = $response->getOriginalContent();
        $projectId = $content[0]->id;
        $addMemberOneData = [
            'project_id' => $projectId,
            'user_id' => $member->id
        ];

        $addMemberOneResponse = $this->post(route('projects.add-member'), $addMemberOneData);

        $taskData = [
            'title' => 'Task1',
            'description' => 'Some random task',
            'project_id' => $projectId,
            'user_id' => $member->id
        ];
        
        $createTaskResponse = $this->post(route('tasks.store'), $taskData);
        $taskContent = $createTaskResponse->getOriginalContent();
        $taskId = $taskContent[0]->id;
        $createTaskResponse->assertJson(
            [
                [
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'project_id' => $taskData['project_id'],
                    'user_id' => $taskData['user_id']
                ]
                
            ]
        );

        $taskStatusChange = [
            'status' => 'IN_PROGRESS'
        ];

        $changeTaskStatusResponse = $this->put(route('tasks.update', $taskId), $taskStatusChange);
        $changeTaskStatusResponse->assertJson(
            [
                'messages' => 'Successfully update task.'
            ]
        );
    }
}

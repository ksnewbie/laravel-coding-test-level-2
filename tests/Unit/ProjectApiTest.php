<?php

namespace Tests\Unit;

use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateProject()
    {
        $user = User::factory()->create(['role' => 'product_owner']);
        $member = User::factory()->create(['role' => 'member']);
        $memberTwo = User::factory()->create(['role' => 'member']);
        Sanctum::actingAs(
            $user,
            ['manage-projects', 'manage-tasks']
        );

        $data = [
            'name' => 'TestProject',
        ];
        $response = $this->post(route('projects.store'), $data);
        $response->assertOk();
        $response->assertJson(
            [
                [
                    'name' => $data['name'],
                    'user_id' => $user->id,
                ]
            ]
        );
        $this->assertDatabaseHas(
            'projects',
            [
                'name' => $data['name']
            ]
        );
        $content = $response->getOriginalContent();
        $projectId = $content[0]->id;
        $addMemberOneData = [
            'project_id' => $projectId,
            'user_id' => $member->id
        ];
        $addMemberTwoData = [
            'project_id' => $projectId,
            'user_id' => $memberTwo->id
        ];

        $addMemberOneResponse = $this->post(route('projects.add-member'), $addMemberOneData);
        $addMemberOneResponse->assertJson(
            [
                'messages' => 'Successfully added member to project.'
            ]
        );

        $addMemberTwoResponse = $this->post(route('projects.add-member'), $addMemberTwoData);
        $addMemberTwoResponse->assertJson(
            [
                'messages' => 'Successfully added member to project.'
            ]
        );
    }
}

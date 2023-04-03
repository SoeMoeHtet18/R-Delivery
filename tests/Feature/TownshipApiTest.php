<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TownshipApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_townships(): void
    {
        $response = $this->getJson('/api/townships');
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'city_id',
                    'deleted_at'
                ]
                ],
                'message',
                'status'
        ]);
    }
}

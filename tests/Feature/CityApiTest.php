<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_cities(): void
    {
        $out = 'test_get_all_cities';
        var_dump($out);
        
        $response = $this->getJson('/api/cities');
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ],
            'message',
            'status'
        ]);
    }
}

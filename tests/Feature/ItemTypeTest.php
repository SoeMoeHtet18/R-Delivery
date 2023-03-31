<?php

namespace Tests\Feature;

use App\Models\ItemType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTypeTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function get_authenticated_user()
    {
        $admin_phone_number = config('app.admin_phone_number');
        $admin = User::select('users.*')->where('phone_number',$admin_phone_number)->first();
        $this->actingAs($admin);
        return $admin;
    }
    /**
     * A basic feature test example.
     */
    public function test_item_type_web(): void
    {   
        $out = "test_item_type_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        $response = $this->get('/itemtypes');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-item-type-data?_=1680250583102&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=name&columns%5B1%5D%5Bname%5D=name&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=action&columns%5B2%5D%5Bname%5D=action&columns%5B2%5D%5Bsearchable%5D=false&columns%5B2%5D%5Borderable%5D=false&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search=&start=0";
        $response = $this->getJson($dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'action',
                    'DT_RowIndex'
                ]
            ]

        ]);
    }

    public function test_get_create_item_type_web(): void
    {
        $out = "test_get_create_item_type_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/itemtypes/create');
        $response->assertStatus(200);
    }

    public function test_store_create_item_type(): void
    {
        $out = "test_store_create_item_type";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $response = $this->post('/itemtypes', [
            'name' => $this->faker->name,
        ]);
        $response->assertStatus(302);
    }

    public function test_item_type_detail_web(): void 
    {
        $out = "test_item_type_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $item_type_id = ItemType::all()->random()->id;
        $response = $this->get('/itemtypes/' . $item_type_id);
        $response->assertStatus(200);
    }

    public function test_update_item_type(): void
    {
        $out = "test_update_item_type";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $rand_item_type_id = ItemType::all()->random()->id;
        $response = $this->put('/itemtypes/' . $rand_item_type_id, [
            'name' => $this->faker->name,
        ]);
        $response->assertStatus(302);
    }

    public function test_delete_item_type(): void 
    {   
        $out = "test_delete_item_type";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $item_type_id = ItemType::all()->random()->id;
        
        $response = $this->delete('/itemtypes/' . $item_type_id);
        $response->assertStatus(302);
    }
}

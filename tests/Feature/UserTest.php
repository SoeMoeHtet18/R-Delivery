<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
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
    public function test_user_web(): void
    {   
        $out = "test_user_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        $response = $this->get('/users');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-users-data?_=1680241579507&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=name&columns%5B1%5D%5Bname%5D=name&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=phone_number&columns%5B2%5D%5Bname%5D=phone_number&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=email&columns%5B3%5D%5Bname%5D=email&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=action&columns%5B4%5D%5Bname%5D=action&columns%5B4%5D%5Bsearchable%5D=false&columns%5B4%5D%5Borderable%5D=false&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search=&start=0";
        $response = $this->getJson($dt_url);
        $response->assertStatus(200)->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'phone_number',
                    'email',
                    'email_verified_at',
                    'device_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'action',
                    'DT_RowIndex'
                ]
            ]

        ]);
    }

    public function test_get_create_user_web(): void
    {
        $out = "test_get_create_user_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/users/create');
        $response->assertStatus(200);
    }

    public function test_store_create_user(): void
    {
        $out = "test_store_create_user";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $rand_password = $this->faker->password;

        $response = $this->post('/users', [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email,
            'password' => $rand_password,
            'password_confirmation' => $rand_password
        ]);
        $response->assertStatus(302);
    }

    public function test_user_detail_web(): void 
    {
        $out = "test_user_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $user_id = User::all()->random()->id;
        $response = $this->get('/users/' . $user_id);
        $response->assertStatus(200);
    }

    public function test_update_user(): void
    {
        $out = "test_update_user";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $rand_password = $this->faker->password;
        $rand_user_id = User::all()->random()->id;
        $response = $this->put('/users/' . $rand_user_id, [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email,
            'password' => $rand_password,
            'password_confirmation' => $rand_password,
            'id' =>  $rand_user_id
        ]);
        $response->assertStatus(302);
    }

    public function test_delete_user(): void 
    {   
        $out = "test_delete_user";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $user_id = User::all()->random()->id;
        
        $response = $this->delete('/users/' . $user_id);
        $response->assertStatus(302);
    }

}

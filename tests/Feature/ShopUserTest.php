<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\ShopUser;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShopUserTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    /**
     * A basic feature test example.
     */
    public function get_authenticated_user()
    {
        $admin_phone_number = config('app.admin_phone_number');
        $admin = User::select('users.*')->where('phone_number',$admin_phone_number)->first();
        $this->actingAs($admin);
        return $admin;
    }

    public function test_shop_user_web(): void
    {   
        $out = "test_shop_user_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        $response = $this->get('/shopusers');
        $response->assertStatus(200);

        $dt_url = "/ajax-get-shop-users-data?_=1680256671796&columns%5B0%5D%5Bdata%5D=DT_RowIndex&columns%5B0%5D%5Bname%5D=id&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=name&columns%5B1%5D%5Bname%5D=name&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=phone_number&columns%5B2%5D%5Bname%5D=phone_number&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=email&columns%5B3%5D%5Bname%5D=email&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=action&columns%5B4%5D%5Bname%5D=action&columns%5B4%5D%5Bsearchable%5D=false&columns%5B4%5D%5Borderable%5D=false&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&draw=1&length=10&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&search=&start=0";
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
                    'password',
                    'device_id',
                    'remember_token',
                    'created_at',
                    'updated_at',
                    'shop_id',
                    'token',
                    'refresh_token',
                    'deleted_at',
                    'action',
                    'DT_RowIndex'
                ]
            ]
        ]);
    }

    public function test_get_create_shop_user_web(): void
    {
        $out = "test_get_create_shop_user_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();
        
        $response = $this->get('/shopusers/create');
        $response->assertStatus(200);
    }

    public function test_store_shop_user(): void
    {
        $out = "test_store_create_shop_user";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $password = $this->faker->password(8,20);
        $shop_id = Shop::all()->random()->id;
        $response = $this->post('/shopusers', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'password' => $password,
            'password_confirmation' =>$password,
            'shop_id' => $shop_id
        ]);
        $response->assertStatus(302)
        ->assertRedirect('/shopusers');
    }

    public function test_shop_user_detail_web(): void 
    {
        $out = "test_shop_user_detail_web";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $shopuser_id = ShopUser::all()->random()->id;
        $response = $this->get('/shopusers/' . $shopuser_id);
        $response->assertStatus(200);
    }

    public function test_update_shop_user(): void
    {
        $out = "test_update_shop_user";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $shopuser_id = ShopUser::all()->random()->id;
        $password = $this->faker->password(8,20);
        $shop_id = Shop::all()->random()->id;
        $response = $this->put('/shopusers/' . $shopuser_id, [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'password' => $password,
            'password_confirmation' =>$password,
            'shop_id' => $shop_id,
            'id' => $shopuser_id
        ]);
        $response->assertStatus(302)
        ->assertRedirect('/shopusers/' . $shopuser_id);
    }

    public function test_delete_shop_user(): void 
    {   
        $out = "test_delete_shop_user";
        var_dump($out);
        $admin = $this->get_authenticated_user();

        $shopuser_id = ShopUser::all()->random()->id;
        
        $response = $this->delete('/shopusers/' . $shopuser_id);
        $response->assertStatus(302)
        ->assertRedirect('/shopusers');
    }
}

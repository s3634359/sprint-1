<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     * 
     * @return void
     */

    /** @test */
    public function create_accounts()
    {
        $this->assertCount(0, User::all());
        factory(User::class)->create();
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function only_logged_in_users_can_see_the_accounts_list()
    {
        $response = $this->get('/home')->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_the_accounts_list()
    {
        $response = $this->actingAs(factory(User::class)->create());
    
        $response = $this->get('/home')->assertOk();
    }

}

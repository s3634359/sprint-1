<?php

namespace Tests\Feature;

use App\User;
use App\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     * 
     * @return void
     */

    /** @test */
    public function only_logged_in_users_can_see_the_location_page()
    {
        $response = $this->get('/location')->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_the_location_page()
    {
        $response = $this->actingAs(factory(User::class)->create());
    
        $response = $this->get('/location')->assertOk();
    }

    /** @test */
    public function an_admin_can_add_a_new_location()
    {
        $response = $this->actingAs(factory(User::class)->create());
                
        $this->assertCount(0, Location::all());

        factory(Location::class)->create();
        
        $response = $this->post('/locationSubmit', [
            'name' => 'Test Location',
            'x_axis' => 12.52,
            'y_axis' => 15.52,
            'description' => 'Test Description',
            'min_time' => 60,
        ]);

        $this->assertCount(2, Location::all());
    }

    /** @test */
    public function an_assistant_can_add_a_new_location()
    {
    $response = $this->actingAs(factory(User::class)->create([
        'position' => 'assistant'
    ]));          
                
        $this->assertCount(0, Location::all());

        factory(Location::class)->create();
        
        $response = $this->post('/locationSubmit', [
            'name' => 'Test Location',
            'x_axis' => 12.52,
            'y_axis' => 15.52,
            'description' => 'Test Description',
            'min_time' => 60,
        ]);

        $this->assertCount(2, Location::all());
    }

}

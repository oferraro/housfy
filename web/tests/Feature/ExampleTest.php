<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\Office;

class ApiTest extends TestCase
{
    // Test, get all offices
    public function testApiGetAllOffices()
    {
        $response = $this->get('/api/offices');
        $response->assertStatus(200);
    }
    // Test Add/Post a new office
    public function testApiPostOffice()
    {
        $response = $this->post('/api/offices', [
            "name" => "test name",
            "address" => "test address"
        ]);
        $response->assertStatus(201);
    }
    // Test add and delete the new office
    public function testApiDeleteOffice()
    {
        $response = $this->post('/api/offices', [
            "name" => "test name",
            "address" => "test address"
        ]);
        $addedOffice = (\json_decode($response->getContent()));
        $response = $this->delete('/api/offices/' . $addedOffice->id);
        $response->assertStatus(200);
    }
    // Test update an office
    public function testApiUpdateOffice()
    {
        $office = Office::first();
        $newName = "new name";
        $response = $this->put('/api/offices/' . $office->id, [
            "name" => $newName,
            "address" => "new adddress"
        ]);
        $response->assertStatus(200);
        $updateddOffice = (\json_decode($response->getContent()));
        $this->assertTrue($updateddOffice->name == $newName);
    }
}

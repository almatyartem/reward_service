<?php

namespace Tests\Feature;

use App\Models\Reward;
use Illuminate\Support\Facades\Artisan;
use PDO;
use Tests\TestCase;

class RewardsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');
    }
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_401_response(): void
    {
        $response = $this->get('/api/healthcheck');

        $response->assertStatus(401);
    }

    public function test_the_application_returns_a_403_response(): void
    {
        $response = $this->get('/api/healthcheck', ['Authorization' => 'Bearer incorrecttoken']);

        $response->assertStatus(403);
    }

    public function test_the_application_returns_a_404_response(): void
    {
        $response = $this->get('/api/wrong_uri', $this->getHeaders());

        $response->assertStatus(404);
    }

    public function test_the_application_returns_a_200_response(): void
    {
        $response = $this->get('/api/healthcheck', $this->getHeaders());

        $response->assertStatus(200);
    }

    public function test_successfully_show_reward() : void
    {
        $reward = Reward::factory()->create();
        $response = $this->get('/api/reward/'.$reward->id, $this->getHeaders());

        $response->assertStatus(200);

        $response->assertJson([
            "details" => $reward->details,
        ]);
    }

    public function test_successfully_show_reward_after_changes() : void
    {
        $reward = Reward::factory()->create();
        $this->get('/api/reward/'.$reward->id, $this->getHeaders());

        $reward->update(['uid' => 'updated_uud']);

        $response = $this->get('/api/reward/'.$reward->id, $this->getHeaders());

        $response->assertJson([
            "uid" => 'updated_uud',
        ]);

        $reward->delete();

        $response = $this->get('/api/reward/'.$reward->id, $this->getHeaders());

        $response->assertStatus(404);
    }

    public function test_wrong_id_while_show_reward() : void
    {
        $response = $this->get('/api/reward/9999', $this->getHeaders());

        $response->assertStatus(404);
    }


    public function test_successfully_create_reward() : void
    {
        $details = json_encode([
            'title' => 'Test reward title',
            'description' => 'Test reward description',
        ]);

        $response = $this->post('/api/reward', ['details' => $details], $this->getHeaders());

        $response->assertStatus(200);

        $this->assertDatabaseCount('rewards', 1);
        $this->assertDatabaseHas('rewards', [
            'details' => $details,
        ]);
    }

    public function test_validation_error_while_create_reward() : void
    {
        $details = json_encode([
            'title' => 'Test reward title',
            'description' => 'Test reward description',
        ]);

        $response = $this->post('/api/reward', ['wrong_key' => $details], $this->getHeaders());

        $response->assertStatus(422);

        $response = $this->post('/api/reward', ['details' => 'wrong_value'], $this->getHeaders());

        $response->assertStatus(422);

        $this->assertDatabaseCount('rewards', 0);
    }

    public function test_successfully_update_reward() : void
    {
        $reward = Reward::factory()->create();

        $details = json_encode([
            'title' => 'Updated reward title',
            'description' => 'Updated reward description',
        ]);

        $response = $this->put('/api/reward/'.$reward->id, ['details' => $details], $this->getHeaders());

        $response->assertStatus(200);

        $this->assertDatabaseCount('rewards', 1);
        $this->assertDatabaseHas('rewards', [
            'details' => $details,
        ]);
    }

    public function test_validation_error_while_update_reward() : void
    {
        $reward = Reward::factory()->create();

        $details = json_encode([
            'title' => 'Updated reward title',
            'description' => 'Updated reward description',
        ]);

        $response = $this->put('/api/reward/'.$reward->id, ['wrong_key' => $details], $this->getHeaders());

        $response->assertStatus(422);

        $response = $this->put('/api/reward/'.$reward->id, ['details' => 'wrong_value'], $this->getHeaders());

        $response->assertStatus(422);

        $this->assertDatabaseCount('rewards', 1);
        $this->assertDatabaseMissing('rewards', [
            'details' => $details,
        ]);
        $this->assertDatabaseMissing('rewards', [
            'details' => 'wrong_value',
        ]);
    }

    public function test_wrong_id_while_update_reward() : void
    {
        $details = json_encode([
            'title' => 'Updated reward title',
            'description' => 'Updated reward description',
        ]);

        $response = $this->put('/api/reward/9999', ['details' => $details], $this->getHeaders());

        $response->assertStatus(404);
    }

    public function test_successfully_destroy_reward() : void
    {
        $reward = Reward::factory()->create();
        $response = $this->delete('/api/reward/'.$reward->id, [], $this->getHeaders());

        $response->assertStatus(204);
        $this->assertDatabaseCount('rewards', 0);
    }

    public function test_wrong_id_while_delete_reward() : void
    {
        $response = $this->delete('/api/reward/9999', [], $this->getHeaders());

        $response->assertStatus(404);
    }

    public function test_successfully_attach_reward_to_user() : void
    {
        $reward = Reward::factory()->create();

        $response = $this->post('/api/reward/'.$reward->id.'/attach_to_user', ['uid' => 'testUid'], $this->getHeaders());

        $response->assertStatus(200);

        $this->assertDatabaseHas('rewards', [
            'uid' => 'testUid',
        ]);
    }

    public function test_wrong_id_while_attach_reward_to_user() : void
    {
        $response = $this->post('/api/reward/9999/attach_to_user', ['uid' => 'testUid'], $this->getHeaders());

        $response->assertStatus(404);
    }

    protected function getHeaders() : array
    {
        return ['Authorization' => 'Bearer '.env('API_TOKEN')];
    }
}

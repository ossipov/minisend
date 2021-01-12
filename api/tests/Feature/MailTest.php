<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use App\Jobs\SendEmailJob;
use App\Models\Mail;
use App\Models\User;
use Database\Seeders\MailSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MailTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /** @test */
    public function email_should_be_added_to_database_when_correct_payload_received()
    {
        Queue::fake();
        Storage::fake('local');

        $file1 = UploadedFile::fake()->create('price.xlsx','240');
        $file2 = UploadedFile::fake()->create('about.doc','340');

        $clientRequest = [
            'from_email' => 'sender@fakemail.com',
            'to_email' => 'recipient@fakemail.com',
            'subject' => 'Fake subject',
            'text' => 'Fake message',
            'html' => '<b>Fake message</b>',
            'attachments' => [$file1, $file2]
        ];

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->postJson('/api/mail', $clientRequest);
        $response->assertStatus(201);

        Storage::disk('local')->assertExists('/attachments/'.$file1->hashName());
        Storage::disk('local')->assertExists('/attachments/'.$file2->hashName());

        $email = Mail::first();
        $this->assertEquals($email->from_email, $clientRequest['from_email']);

        Queue::assertPushed(SendEmailJob::class, function ($job) use ($email) {
            return $job->email->is($email);
        });
    }

    /** @test */
    public function guest_can_get_list_of_all_emails()
    {
        $user = User::factory()->create();

        Mail::factory()->times(50)->create([
            'to_email' => 'dmitry@ossipov.me'
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->get('/api/mail');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [0 => [
                'to_email' => 'dmitry@ossipov.me'
            ]]
        ]);
        $response->assertJson(['current_page' => 1]);

        $response = $this->get('/api/mail?page=2');
        $response->assertStatus(200);
        $response->assertJson(['current_page' => 2]);
    }

    /** @test */
    public function guest_can_get_list_of_filtered_emails_by_subject()
    {
        $user = User::factory()->create();

        Mail::factory()->times(10)->create([
            'user_id' => $user->id,
            'subject' => "Hooray! You've got an award for being so awesome 🎉"
        ]);
        Mail::factory()->times(40)->create();

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('get', '/api/mail', [
            'subject' => 'award'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['current_page' => 1]);
        $response->assertJson(['total' => 10]);
    }

    /** @test */
    public function guest_can_get_list_of_filtered_emails_by_status()
    {
        $user = User::factory()->create();

        Mail::factory()->times(10)->create([
            'user_id' => $user->id,
            'status' => 'failed'
        ]);
        Mail::factory()->times(10)->create([
            'user_id' => $user->id,
            'status' => 'posted'
        ]);
        Mail::factory()->times(10)->create([
            'user_id' => $user->id,
            'status' => 'sent'
        ]);

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('get', '/api/mail', [
            'status' => 'posted'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['current_page' => 1]);
        $response->assertJson(['total' => 10]);
    }

    /** @test */
    public function guest_can_get_list_of_emails_for_recipient()
    {
        $user = User::factory()->create();

        Mail::factory()->times(10)->create([
            'user_id' => $user->id,
            'to_email' => 'dmitry@ossipov.me'
        ]);
        Mail::factory()->times(40)->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('get', '/api/mail', [
            'to' => 'dmitry@ossipov.me'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['current_page' => 1]);
        $response->assertJson(['total' => 10]);
    }

    /** @test */
    public function guest_can_get_a_single_email()
    {
        $user = User::factory()->create();

        Mail::factory()->create([
            'user_id' => $user->id,
            'to_email' => 'dmitry@ossipov.me'
        ]);
        Mail::factory()->times(40)->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('get', '/api/mail', [
            'id' => 1
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => 1,
            'to_email' => 'dmitry@ossipov.me'
        ]);
    }

    /** @test */
    public function guest_cant_see_mails_of_other_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Mail::factory(10)->create([
            'user_id' => $user1->id,
        ]);
        Mail::factory(40)->create([
            'user_id' => $user2->id,
        ]);

        Sanctum::actingAs($user1, ['*']);
        $this->json('get', '/api/mail')
            ->assertStatus(200)
            ->assertJson([
                'total' => 10,
            ]);
    }

    /** @test */
    public function unauthenticated_cant_view_mails()
    {
        $user1 = User::factory()->create();

        Mail::factory(10)->create([
            'user_id' => $user1->id,
        ]);

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');
        $this->json('get', '/api/mail');
    }

    /** @test */
    public function admin_can_view_all_users_mails()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Mail::factory(10)->create([
            'user_id' => $admin->id,
        ]);
        Mail::factory(10)->create([
            'user_id' => $user1->id,
        ]);
        Mail::factory(10)->create([
            'user_id' => $user2->id,
        ]);

        Sanctum::actingAs($admin, ['*']);
        $this->json('get', '/api/mail')
            ->assertStatus(200)
            ->assertJson([
                'total' => 30,
            ]);
    }
}

<?php

namespace Tests\Feature;

use App\Jobs\SendEmailJob;
use App\Models\Mail;
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
        Mail::factory()->times(50)->create([
            'to_email' => 'dmitry@ossipov.me'
        ]);

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
        Mail::factory()->times(10)->create([
            'subject' => "Hooray! You've got an award for being so awesome 🎉"
        ]);
        Mail::factory()->times(40)->create();

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
        Mail::factory()->times(10)->create([
            'status' => 'failed'
        ]);
        Mail::factory()->times(10)->create([
            'status' => 'posted'
        ]);
        Mail::factory()->times(10)->create([
            'status' => 'sent'
        ]);

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
        Mail::factory()->times(10)->create([
            'to_email' => 'dmitry@ossipov.me'
        ]);
        Mail::factory()->times(40)->create();

        $response = $this->json('get', '/api/mail', [
            'to_email' => 'dmitry@ossipov.me'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['current_page' => 1]);
        $response->assertJson(['total' => 10]);
    }

    /** @test */
    public function guest_can_get_a_single_email()
    {
        Mail::factory()->create([
            'to_email' => 'dmitry@ossipov.me'
        ]);
        Mail::factory()->times(40)->create();

        $response = $this->json('get', '/api/mail', [
            'id' => 1
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => 1,
            'to_email' => 'dmitry@ossipov.me'
        ]);
    }
}

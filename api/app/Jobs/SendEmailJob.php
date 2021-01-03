<?php

namespace App\Jobs;

use App\Mail\DefaultEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    /**
     * Create a new job instance.
     * @param \App\Models\Mail $email
     */
    public function __construct(\App\Models\Mail $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email->to_email)->send(new DefaultEmail($this->email));

        $mail = \App\Models\Mail::findOrFail($this->email->id);
        $mail->status = empty(Mail::failures()) ? 'sent' : 'failed';
        $mail->save();
    }
}

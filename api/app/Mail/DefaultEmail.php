<?php

namespace App\Mail;

use App\Models\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DefaultEmail extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail;

    /**
     * Create a new message instance.
     *
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): DefaultEmail
    {
        $email = $this->view('templates.default', [
                'subject' => $this->mail->subject,
                'html' => html_entity_decode($this->mail->html),
            ])->text('templates.plain', [
                'text' => $this->mail->text
            ])
            ->from($this->mail->from_email, $this->mail->from_name)
            ->to($this->mail->to_email, $this->mail->to_name)
            ->subject($this->mail->subject);

        $attachments = json_decode($this->mail->attachments);
        if (!empty($attachments)){
            foreach ($attachments as $attachment) {
                $email->attachFromStorage($attachment->path, $attachment->name);
            }
        }
        return $email;
    }
}

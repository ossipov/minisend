<?php
namespace App\Services;

use App\Models\Mail;
use Html2Text\Html2Text;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MailService
{
    /**
     * One mail by Id
     *
     * @param $id
     * @return Mail|Mail[]|Collection|Model|null
     */
    public function mailById($id): Mail
    {
        return Mail::findOrFail($id);
    }

    /**
     * Filtered & paginated & sorted list of mails based on request
     *
     * @param $request
     * @return LengthAwarePaginator
     */
    public function filteredListOfMails($request): LengthAwarePaginator
    {
        $mail = Mail::select([
            'id', 'status', 'from_name', 'from_email', 'to_name', 'to_email', 'subject'
        ]);
        if (isset($request->subject)) {
            $mail->containsSubject($request->subject);
        }
        if (isset($request->status)) {
            $mail->hasStatus($request->status);
        }
        if (isset($request->to)) {
            $mail->dedicatedFor($request->to);
        }
        if (isset($request->from)) {
            $mail->sentBy($request->from);
        }
        $mail->orderBy('updated_at', 'DESC');

        return $mail->paginate(10);
    }

    /**
     * Adds mail to database
     *
     * @param $request
     * @return Mail|Model
     */
    public function createMail($request): Mail
    {
        $request['text'] = $this->convertHtml2Text($request['html']);
        $request['attachments'] = $this->extractAttachments($request);

        return Mail::create($request);
    }

    /**
     * Converts Html 2 Text removing first occurrence of Paragraph in List's item
     * to have proper line breaks
     *
     * @param $html
     * @return string
     */
    private function convertHtml2Text($html): string
    {
        $html = preg_replace('/<li><p>(.*?)<\/p>/i', '<li>$1', $html);
        return (new Html2Text($html))->getText();
    }

    private function extractAttachments($request)
    {
        $attachments = [];
        $request['attachments'] = $request['attachments'] ?? [];
        foreach($request['attachments'] as $attachment) {
            $attachments[] = [
                'name' => $attachment->getClientOriginalName(),
                'size' => $attachment->getSize(),
                'path' => $attachment->store('attachments'),
            ];
        }
        return json_encode($attachments);
    }
}

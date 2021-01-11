<?php
namespace App\Services;

use App\Http\Requests\MailRequest;
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
        if(auth()->user()->isAdmin()) {
            return Mail::findOrFail($id);
        }
        return auth()->user()->mails()->findOrFail($id);
    }

    /**
     * Filtered & paginated & sorted list of mails based on request
     *
     * @param $request
     * @return LengthAwarePaginator
     */
    public function filteredListOfMails(MailRequest $request): LengthAwarePaginator
    {
        $mail = Mail::select([
            'id', 'status', 'from_name', 'from_email', 'to_name', 'to_email', 'subject'
        ]);
        if (! auth()->user()->isAdmin()) {
            $mail->where('user_id', auth()->id());
        }
        if ($request->filled('subject')) {
            $mail->containsSubject($request->subject);
        }
        if ($request->filled('status')) {
            $mail->hasStatus($request->status);
        }
        if ($request->filled('to')) {
            $mail->dedicatedFor($request->to);
        }
        if ($request->filled('from')) {
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
    public function createMail(MailRequest $request): Mail
    {
        $request = array_merge($request->all(), [
            'text' => $this->convertHtml2Text($request->html),
            'attachments' => $this->extractAttachments($request),
        ]);
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

    private function extractAttachments(MailRequest $request)
    {
        $attachments = [];
        $request->attachments = $request->attachments ?? [];
        foreach($request->attachments as $attachment) {
            $attachments[] = [
                'name' => $attachment->getClientOriginalName(),
                'size' => $attachment->getSize(),
                'path' => $attachment->store('attachments'),
            ];
        }
        return json_encode($attachments);
    }
}

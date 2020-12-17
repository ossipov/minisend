<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailRequest;
use App\Jobs\SendEmailJob;
use App\Models\Mail;
use Exception;
use Illuminate\Http\JsonResponse;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param MailRequest $request
     * @return JsonResponse
     */
    public function index(MailRequest $request): JsonResponse
    {
        if (isset($request->id)) {
            return response()->json(Mail::findOrFail($request->id));
        }

        $mail = Mail::select();
        if (isset($request->subject)) {
            $mail->containsSubject($request->subject);
        }
        if (isset($request->status)) {
            $mail->hasStatus($request->status);
        }
        if (isset($request->to_email)) {
            $mail->dedicatedFor($request->to_email);
        }

        return response()->json($mail->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MailRequest $request
     * @return JsonResponse
     */
    public function store(MailRequest $request): JsonResponse
    {
        $request = $request->validated();

        $attachments = [];
        foreach($request['attachments'] as $attachment) {
            $attachments[] = [
                'name' => $attachment->name,
                'size' => $attachment->getSize(),
                'path' => $attachment->store('attachments'),
            ];
        }
        $request['attachments'] = json_encode($attachments);
        $mail = Mail::create($request);

        SendEmailJob::dispatch($mail);
        return response()->json($mail, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param MailRequest $request
     * @param Mail $mail
     * @return JsonResponse
     */
    public function update(MailRequest $request, Mail $mail): JsonResponse
    {
        $mail = Mail::findOrFail($mail);
        $mail->fill($request->except(['id']));
        $mail->save();
        return response()->json($mail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MailRequest $mail
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(MailRequest $mail): JsonResponse
    {
        $mail = Mail::findOrFail($mail->id);
        if ($mail->delete()) {
            return response()->json(null, 204);
        }
        return response()->json(null, 404);
    }
}

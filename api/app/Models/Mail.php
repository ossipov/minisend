<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mail
 *
 * @mixin Builder
 * @mixin Eloquent
 * @method static Builder|Mail containsSubject($subject)
 * @method static Builder|Mail dedicatedFor($to)
 * @method static Builder|Mail sentBy($from)
 * @method static Builder|Mail hasStatus($status)
 * @method static Builder|Mail newModelQuery()
 * @method static Builder|Mail newQuery()
 * @method static Builder|Mail query()
 * @property int $id
 * @property string $status
 * @property string|null $from_name
 * @property string $from_email
 * @property string|null $to_name
 * @property string $to_email
 * @property string $subject
 * @property string $text
 * @property string|null $html
 * @property string|null $attachments
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Mail whereAttachments($value)
 * @method static Builder|Mail whereCreatedAt($value)
 * @method static Builder|Mail whereDeletedAt($value)
 * @method static Builder|Mail whereFromEmail($value)
 * @method static Builder|Mail whereFromName($value)
 * @method static Builder|Mail whereHtml($value)
 * @method static Builder|Mail whereId($value)
 * @method static Builder|Mail whereStatus($value)
 * @method static Builder|Mail whereSubject($value)
 * @method static Builder|Mail whereText($value)
 * @method static Builder|Mail whereToEmail($value)
 * @method static Builder|Mail whereToName($value)
 * @method static Builder|Mail whereUpdatedAt($value)
 */
class Mail extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_name',
        'from_email',
        'to_name',
        'to_email',
        'subject',
        'text',
        'html',
        'attachments'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($mail) {
            $mail->user_id = auth()->id() ?? $mail->user_id;
        });
    }

    public function scopeContainsSubject($query, $subject)
    {
        $query->where('subject', 'like', '%' . $subject . '%');
    }

    public function scopeHasStatus($query, $status)
    {
        $query->where('status', $status);
    }

    public function scopeDedicatedFor($query, $to)
    {
        $query->where(function ($q) use ($to) {
            return $q->where('to_name', 'like', '%' . $to . '%')
                ->orWhere('to_email', 'like', '%' . $to . '%');
        });
    }

    public function scopeSentBy($query, $from)
    {
        $query->where(function ($q) use ($from) {
            return $q->where('from_name', 'like', '%' . $from . '%')
                ->orWhere('from_email', 'like', '%' . $from . '%');
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

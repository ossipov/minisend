<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mail
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Mail containsSubject($subject)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail dedicatedFor($to_email)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail hasStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mail query()
 * @mixin \Eloquent
 */
class Mail extends Model
{
    use HasFactory;

    protected $fillable = ['from_email', 'to_email', 'subject', 'text', 'html', 'attachments'];

    public function scopeContainsSubject($query, $subject)
    {
        $query->where('subject', 'like', '%' . $subject . '%');
    }

    public function scopeHasStatus($query, $status)
    {
        $query->where('status', $status);
    }

    public function scopeDedicatedFor($query, $to_email)
    {
        $query->where('to_email', $to_email);
    }

}

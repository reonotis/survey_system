<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $notification_mail_flg
 * @property string $notification_mail_title
 * @property string $notification_mail_address
 * @property string $notification_mail_message
 * @property int $auto_reply_mail_flg
 * @property string $auto_reply_mail_title
 * @property string $auto_reply_mail_message
 */
class MailSetting extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mail_setting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_setting_id',
        'notification_mail_flg',
        'notification_mail_title',
        'notification_mail_address',
        'notification_mail_message',
        'auto_reply_mail_flg',
        'auto_reply_mail_title',
        'auto_reply_mail_message',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        ];
    }
}

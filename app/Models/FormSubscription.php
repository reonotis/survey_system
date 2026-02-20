<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 */
class FormSubscription extends Model
{
    use HasFactory, SoftDeletes;

    //
    protected $fillable = [
        'form_setting_id',
        'user_id',
        'stripe_subscription_id',
        'status',
        'current_period_end',
    ];

    // Stripeのstatus定数（string）
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PAST_DUE = 'past_due';
}

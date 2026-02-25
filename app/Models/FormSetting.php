<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Cashier\Billable;


/**
 * @property int $id
 * @property string $host_name
 * @property string $form_name
 * @property string $title
 * @property string $route_name
 * @property string $admin_email
 * @property int $is_draft_item
 * @property int $design_type
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property PublicationStatus $publication_status
 * @property ?int $max_applications
 *
 * @property Collection<int, FormItem> $formItems
 * @property Collection<int, FormItemDraft> $draftFormItems
 * @property ?MessageSetting $message
 * @property ?MailSetting $mailSetting
 */
class FormSetting extends Model
{
    use HasFactory, SoftDeletes, Billable;

    const PUBLICATION_STATUS_DISABLE = 0;

    const BILLING_STATUS_LIST = [
        0 => '未請求',
        1 => '請求中',
        2 => '入金確認済み',
    ];

    protected $table = 'form_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'host_name',
        'form_name',
        'title',
        'route_name',
        'admin_email',
        'start_date',
        'end_date',
        'max_applications',
        'image_directory',
        'css_filename',
        'banner_filename',
        'is_draft_item',
        'publication_status',
        'design_type',
        'created_by_admin',
        'created_by_user',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'max_applications' => 'integer',
            'publication_status' => PublicationStatus::class,
        ];
    }

    /**
     * @return HasMany
     */
    public function formItems(): HasMany
    {
        return $this->hasMany(FormItem::class)->orderBy('sort');
    }

    /**
     * @return HasMany
     */
    public function draftFormItems(): HasMany
    {
        return $this->hasMany(FormItemDraft::class)->orderBy('sort');
    }

    /**
     * @return HasOne
     */
    public function message(): HasOne
    {
        return $this->hasOne(MessageSetting::class);
    }

    /**
     * @return HasOne
     */
    public function mailSetting(): HasOne
    {
        return $this->hasOne(MailSetting::class);
    }

    public function applications()
    {
        return $this->hasMany(
            Application::class,
            'form_setting_id'
        );
    }

    /**
     * 課金履歴（過去含むすべて）
     */
    public function subscription()
    {
        return $this->hasMany(FormSubscription::class);
    }

    /**
     * 現在有効な契約（最新1件）
     */
    public function activeSubscription()
    {
        return $this->hasOne(FormSubscription::class)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('current_period_end')
                    ->orWhere('current_period_end', '>', now());
            })
            ->latest();
    }

    public function isPaid(): bool
    {
        return $this->activeSubscription()->exists();
    }
}

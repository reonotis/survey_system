<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $host_name
 * @property string $form_name
 * @property string $title
 * @property string $route_name
 * @property string $admin_email
 * @property int $is_draft_item
 * @property int $design_type
 *
 * @property Collection<int, FormItem> $formItems
 * @property Collection<int, FormItemDraft> $draftFormItems
 * @property ?MessageSetting $message
 * @property ?MailSetting $mailSetting
 */
class FormSetting extends Model
{
    use HasFactory, SoftDeletes;

    const PUBLICATION_STATUS_DISABLE = 0;
    const PUBLICATION_STATUS_ENABLE = 1;
    const PUBLICATION_STATUS_LIST = [
        self::PUBLICATION_STATUS_DISABLE => '非公開',
        self::PUBLICATION_STATUS_ENABLE => '公開',
    ];

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

}

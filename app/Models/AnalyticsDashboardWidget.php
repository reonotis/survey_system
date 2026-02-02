<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $form_setting_id
 * @property int $form_item_id
 * @property int $display_type
 * @property int $item_type
 *
 * @property Collection<int, FormItem> $formItems
 */
class AnalyticsDashboardWidget extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
        protected $table = 'analytics_dashboard_widget';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_setting_id',
        'form_item_id',
        'analytics_title',
        'display_type',
    ];

}

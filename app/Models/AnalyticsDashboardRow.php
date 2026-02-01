<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $row_index
 *
 * @property Collection<int, FormItem> $formItems
 */
class AnalyticsDashboardRow extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
            protected $table = 'analytics_dashboard_rows';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_setting_id',
        'row_index',
        'layout_type',
        'layout_definition',
        'analytics_dashboard_widget_id_1',
        'analytics_dashboard_widget_id_2',
        'analytics_dashboard_widget_id_3',
        'analytics_dashboard_widget_id_4',
    ];

    protected $casts = [
        'layout_definition' => 'array',
    ];
}


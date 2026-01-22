<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 */
class ApplicationSub extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'application_sub'; // ← 実際のテーブル名

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id',
        'form_item_id',
        'answer',
        'answer_text',
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

    /**
     * Get the form item for the application sub.
     *
     * @return BelongsTo
     */
    public function formItem(): BelongsTo
    {
        return $this->belongsTo(FormItem::class, 'form_item_id');
    }

}

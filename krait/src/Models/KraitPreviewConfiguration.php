<?php

namespace MtrDesign\Krait\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class KraitPreviewConfiguration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'table_name',
        'sort_column',
        'sort_direction',
        'columns_order',
        'columns_width',
        'visible_columns',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'columns_order' => 'array',
        'columns_width' => 'array',
        'visible_columns' => 'array',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (KraitPreviewConfiguration $model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->first() ?? abort(404);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

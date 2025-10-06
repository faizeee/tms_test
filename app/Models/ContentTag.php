<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $content_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag whereContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContentTag extends Model
{
    use HasFactory;
    protected $fillable = ['content_id','tag_id'];
}

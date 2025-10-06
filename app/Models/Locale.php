<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Content> $contents
 * @property-read int|null $contents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Locale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Locale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Locale query()
 * @method static \Illuminate\Database\Eloquent\Builder|Locale whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locale whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Locale whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Locale extends Model
{
    use HasFactory;
    protected $fillable = ['code','name'];

    public function contents(){
        return $this->belongsToMany(Content::class,'content_translations','locale_id','content_id')
        ->withPivot(['translation']);
    }

    public function translations(){
        return $this->hasMany(ContentTranslation::class,'locale_id');
    }
}
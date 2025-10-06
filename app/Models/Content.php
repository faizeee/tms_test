<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $key
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Locale> $locales
 * @property-read int|null $locales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\ContentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Content extends Model
{
    use HasFactory;

    protected $fillable = ['key','content'];

    public function locales(){
        return $this->belongsToMany(Locale::class,'content_translations','content_id','locale_id')
        ->withPivot(columns: ['translation']);
    }

    public function translations(){
        return $this->hasMany(ContentTranslation::class,'content_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'content_tags','content_id','tag_id');
    }
}

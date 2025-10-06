<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $content_id
 * @property int $locale_id
 * @property string $translation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Content $content
 * @property-read \App\Models\Locale $locale
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation whereContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation whereLocaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation whereTranslation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContentTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['content_id','locale_id','translation'];

    public function locale(){
        return $this->belongsTo(Locale::class);
    }

    public function content(){
        return $this->belongsTo(Content::class);
    }
}

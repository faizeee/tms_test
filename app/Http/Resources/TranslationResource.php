<?php

namespace App\Http\Resources;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @mixin Content
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "key"=>$this->key,
            "tags"=>$this->tags->pluck("name")->toArray(),
            "translations" => $this->translations->map(function ($t) {
                return [
                    'id' => $t->id,
                    'locale' => $t->locale?->code,
                    'translation' => $t->translation,
                ];
            })->toArray()
        ];
    }
}

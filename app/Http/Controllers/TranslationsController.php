<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveTranslationRequest;
use App\Http\Resources\TranslationResource;
use App\Models\Content;
use App\Models\ContentTranslation;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
* @group Translations
*
* APIs for managing translation contents.
*/
class TranslationsController extends Controller
{
    /**
     * 
     * List all translation contents with optional filters.
     *
     * Retrieve paginated translation sets with their tags and localized translations.
     * You can filter by tag name, translation key, or translation text.
     *
     * @authenticated
     * @queryParam tag string Optional. Filter contents by tag name. Example: desktop
     * @queryParam key string Optional. Filter contents by partial key match. Example: settings.view
     * @queryParam content string Optional. Filter contents by partial translation match. Example: Hello
     *
     * @responseFile 200 storage/responses/translations.index.json
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection<\App\Http\Resources\TranslationResource>
     */
    public function index(Request $request):AnonymousResourceCollection {
        $tag = $request->get('tag');
        $content = $request->get('content');
        $key = $request->get('key');
        $content_translations = Content::with(['translations.locale:id,code', 'tags:id,name'])
        ->when($tag, function($q,$tag){
            $q->whereHas('tags',fn($q) => $q->where('name', $tag));
        })
        ->when($key, function($q, $key){
            $q->where('key','like', "%{$key}%");
        })
        ->when($content, function($q, $content){
            $q->whereHas('translations',fn($q) => $q->where('translation','like', "%{$content}%"));
        })->paginate(100);

        return TranslationResource::collection($content_translations);

    }

    /**
     * Export translations for a specific tag and locale.
     *
     * Returns a JSON object mapping translation keys to their localized values.
     * Typically used to export a set of translations for a frontend or a language file.
     *
     * @authenticated
     * @urlParam tag string required The tag name used to group translation contents. Example: desktop
     * @urlParam locale string optional The locale code to export (default: "en"). Example: fr
     *
     * @response 200 {
     *   "settings.view.68e2b7d08ca87": "Impedit laborum ab nobis.",
     *   "dashboard.title": "Tableau de bord"
     * }
     *
     * @param string $tag
     * @param string $locale
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(string $tag,string $locale = "en"):JsonResponse{
        $translations = ContentTranslation::query()
        ->whereHas('locale', fn($q) => $q->where('code', $locale))
        ->whereHas('content.tags',fn($q)=>$q->where('name', $tag))
        ->with('content:id,key')
        ->get(['content_id', 'translation'])
        ->mapWithKeys(fn($t) => [$t->content->key => $t->translation]);
        return response()->json($translations);
    }

    /**
     * Create a new translation content set.
     *
     * Accepts a translation key, content, associated tag IDs, and an array of localized translations.
     * Each translation entry must include a locale ID and a translation string.
     *
     * @authenticated
     * @bodyParam key string required Unique translation key. Example: settings.view.68e2b7d08ca87
     * @bodyParam content string required Description or context for translators. Example: View settings screen text
     * @bodyParam tags array required List of tag IDs. Example: [1, 2]
     * @bodyParam tags[].id integer The ID of a tag. Example: 1
     * @bodyParam translations array required List of translations by locale.
     * @bodyParam translations[].locale_id integer required Locale ID. Example: 1
     * @bodyParam translations[].translation string required Translated text. Example: Impedit laborum ab nobis.
     *
     * @responseFile 201 storage/responses/translations.create.json
     *
     * @param \App\Http\Requests\SaveTranslationRequest $request
     * @return \App\Http\Resources\TranslationResource
     */
    public function create(SaveTranslationRequest $request):TranslationResource{

        $content =  DB::transaction(function() use ($request){
            $content_data = $request->only(['key','content']);
            return tap(Content::create($content_data),function (Content $content) use ($request){
                $content->tags()->sync(ids: $request->tags);
                $content->translations()->createMany($request->translations);
                $content->load(['translations.locale:id,code', 'tags:id,name']);
            });
        });
        return (new TranslationResource($content))->additional(['message' => 'Translation set created successfully.']);
    }

    /**
     * Update an existing translation content set.
     *
     * Replaces tags and translations for the specified content.
     * If translations already exist, they are deleted and replaced.
     *
     * @authenticated
     * @urlParam content integer required The ID of the content to update. Example: 1
     *
     * @bodyParam key string required Updated translation key. Example: settings.view.68e2b7d08ca87
     * @bodyParam content string required Updated context or description. Example: Updated settings page text
     * @bodyParam tags array required List of tag IDs. Example: [1, 3]
     * @bodyParam translations array required List of updated translations by locale.
     * @bodyParam translations[].locale_id integer required Locale ID. Example: 2
     * @bodyParam translations[].translation string required Translated text. Example: Modifié les paramètres.
     *
     * @responseFile 200 storage/responses/translations.update.json
     *
     * @param \App\Http\Requests\SaveTranslationRequest $request
     * @param \App\Models\Content $content
     * @return \App\Http\Resources\TranslationResource
     */
    public function update(SaveTranslationRequest $request,Content $content):TranslationResource    {
        $content = DB::transaction(function() use ($request,$content){
            $content->update($request->only(['key','content']));
            $content->tags()->sync($request->tags);
            $content->translations()->delete();
            $content->translations()->createMany($request->translations);
            $content->load(['translations.locale:id,code', 'tags:id,name']);
            return $content;
        });
        return (new TranslationResource($content))->additional(['message' => 'Translation updated successfully.']);
    }

}

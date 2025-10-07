<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLocaleRequest;
use App\Models\Locale;
use Illuminate\Http\JsonResponse;

/**
 * @group Locales
 *
 * API for managing locales.
 */
class LocalesController extends Controller
{
    /**
     * 
     * Get a list of available locales.
     *
     * Returns all supported locales ordered by name.  
     * Each locale contains its `id`, `code`, and `name`.
     * @authenticated
     * @response 200 []
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index():JsonResponse{
        $locales = Locale::orderBy("name")->get(['id', 'code', 'name']);
        return response()->json($locales);
    }

    /**
     * Create a new Locale.
     *
     * Accepts a locale `code` and `name`.
     *
     * @authenticated
     * @bodyParam code string required Unique locale code. Example: en, fr, es
     * @bodyParam name string required Descriptive name for locale. Example: English
     *
     * @param \App\Http\Requests\SaveLocaleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SaveLocaleRequest $request): JsonResponse {
        $locale = Locale::create($request->validated());
        return response()->json($locale->only(['id', 'code', 'name']));
    }
}

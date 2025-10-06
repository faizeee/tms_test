<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Locales
 *
 * API for retrieving available locales.
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
     * @response 200 [
     *   { "id": 1, "code": "en", "name": "English" },
     *   { "id": 2, "code": "fr", "name": "French" },
     *   { "id": 3, "code": "es", "name": "Spanish" }
     * ]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke():JsonResponse{
        $locales = Locale::orderBy("name")->get(['id', 'code', 'name']);
        return response()->json($locales);
    }
}

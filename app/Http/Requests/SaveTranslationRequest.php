<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "key" => ["required","string",Rule::unique("contents","key")->ignore($this->route("content"))],
            "content"=>["required","string"],
            "tags"=>["required","array","min:1"],
            "tags.*"=>["integer",Rule::exists("tags","id")],//[1,2,3] to validat
            "translations"=>["required","array","min:1"],
            "translations.*.locale_id"=>['required',Rule::exists("locales","id")],
            "translations.*.translation"=>['required',"string"]
        ];
    }
}

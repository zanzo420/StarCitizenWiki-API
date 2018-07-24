<?php declare(strict_types = 1);

namespace App\Http\Requests;

use App\Models\System\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class TranslationRequest
 */
class TranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $localeCodes = Language::all('locale_code')->keyBy('locale_code');
        $rule = '|string|min:1';
        $rules = [];

        foreach ($localeCodes as $code => $model) {
            if (config('language.english') === $model) {
                $rules[$code] = 'required'.$rule;
            } else {
                $rules[$code] = 'present'.$rule;
            }
        }

        return $rules;
    }
}
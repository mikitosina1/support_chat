<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $locale = substr(
            $request->header('Accept-Language', app()->getLocale()),
            0,
            2
        );

        if (! in_array($locale, ['en', 'de', 'ru'])) {
            $locale = 'en';
        }

        return response()->json([
            'success' => true,
            'translations' => trans('supportchat::chat_lang', [], $locale),
        ]);
    }
}

<?php

namespace Red\Admin\Http;

use JoggApp\GoogleTranslate\GoogleTranslate;
use JoggApp\GoogleTranslate\GoogleTranslateClient;

class GoogleTranslation
{
    public $trans;

    public function __construct()
    {
        $GoogleTranslateClient = new GoogleTranslateClient(['api_key' => env('GOOGLE_TRANSLATE_API_KEY'), 'default_target_translation' => 'ru']);
        $this->trans = new GoogleTranslate($GoogleTranslateClient);
    }

    public function justTranslate($text) {
        return $this->trans->justTranslate($text, 'ru');
    }
}
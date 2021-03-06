<?php

namespace Bazinga\JsTranslationBundle\Tests\Controller;

use Bazinga\Bundle\JsTranslationBundle\Tests\WebTestCase;

class ControllerTest extends WebTestCase
{
    public function testGetTranslations()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/messages.json');
        $response = $client->getResponse();

        $this->assertEquals(<<<JSON
{
    "fallback": "en",
    "defaultDomain": "messages",
    "translations": {"en":{"messages":{"hello":"hello"}}}
}

JSON
        , $response->getContent());
    }

    public function testGetTranslationsWithMultipleLocales()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/messages.json?locales=en,fr');
        $response = $client->getResponse();

        $this->assertEquals(<<<JSON
{
    "fallback": "en",
    "defaultDomain": "messages",
    "translations": {"en":{"messages":{"hello":"hello"}},"fr":{"messages":{"hello":"bonjour"}}}
}

JSON
        , $response->getContent());
    }

    public function testGetTranslationsWithUnknownDomain()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/unknown.json');
        $response = $client->getResponse();

        $this->assertEquals(<<<JSON
{
    "fallback": "en",
    "defaultDomain": "messages",
    "translations": {"en":[]}
}

JSON
        , $response->getContent());
    }

    public function testGetTranslationsWithUnknownLocale()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/foo.json?locales=pt');
        $response = $client->getResponse();

        $this->assertEquals(<<<JSON
{
    "fallback": "en",
    "defaultDomain": "messages",
    "translations": {"pt":[]}
}

JSON
        , $response->getContent());
    }

    public function testGetJsTranslations()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/messages.js');
        $response = $client->getResponse();

        $this->assertEquals(<<<JS
(function (Translator) {
    Translator.fallback      = 'en';
    Translator.defaultDomain = 'messages';
    // en
    Translator.add("hello", "hello", "messages", "en");
})(Translator);

JS
        , $response->getContent());
    }

    public function testGetJsTranslationsWithMultipleLocales()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/messages.js?locales=en,fr');
        $response = $client->getResponse();

        $this->assertEquals(<<<JS
(function (Translator) {
    Translator.fallback      = 'en';
    Translator.defaultDomain = 'messages';
    // en
    Translator.add("hello", "hello", "messages", "en");
    // fr
    Translator.add("hello", "bonjour", "messages", "fr");
})(Translator);

JS
        , $response->getContent());
    }

    public function testGetJsTranslationsWithUnknownDomain()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/unknown.js');
        $response = $client->getResponse();

        $this->assertEquals(<<<JS
(function (Translator) {
    Translator.fallback      = 'en';
    Translator.defaultDomain = 'messages';
    // en
})(Translator);

JS
        , $response->getContent());
    }

    public function testGetJsTranslationsWithUnknownLocale()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/foo.js?locales=pt');
        $response = $client->getResponse();

        $this->assertEquals(<<<JS
(function (Translator) {
    Translator.fallback      = 'en';
    Translator.defaultDomain = 'messages';
    // pt
})(Translator);

JS
        , $response->getContent());
    }

    public function testGetTranslationsWithNumericKeys()
    {
        $client  = static::createClient();

        $crawler  = $client->request('GET', '/translations/numerics.json?locales=en');
        $response = $client->getResponse();

        $this->assertEquals(<<<JSON
{
    "fallback": "en",
    "defaultDomain": "messages",
    "translations": {"en":{"numerics":{"7":"Nos occasions","8":"Nous contacter","12":"pr\u00e9nom","13":"nom","14":"adresse","15":"code postal"}}}
}

JSON
        , $response->getContent());
    }
}

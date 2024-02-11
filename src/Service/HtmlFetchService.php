<?php

declare(strict_types=1);

namespace App\Service;

use voku\helper\HtmlDomParser;

class HtmlFetchService
{
    private \CurlHandle $ch;

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function fetchHtmlFromUrl(string $url): HtmlDomParser|false
    {
        curl_setopt($this->ch, \CURLOPT_URL, $url);
        curl_setopt($this->ch, \CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->ch);

        if ($response === false) {
            error_log('Curl error for url: '.$url);

            return false;
        }

        return HtmlDomParser::str_get_html($response);
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

class BibService
{
    private const FULL_SEARCH_URL   = 'https://stadtbibliothek.osnabrueck.de/suche?p_r_p_arena_urn:arena_search_query=(mediaClass_index:book+OR+mediaClass_index:eBook)+AND+(author_index:"{AUTHOR}"+OR+contributor_index:"{AUTHOR}")+AND+(title_index:"{TITLE}"+OR+titleMain_index:"{TITLE}")';
    private const AUTHOR_SEARCH_URL = 'https://stadtbibliothek.osnabrueck.de/suche?p_r_p_arena_urn:arena_search_query=(mediaClass_index:book+OR+mediaClass_index:eBook)+AND+(author_index:"{AUTHOR}"+OR+contributor_index:"{AUTHOR}")';

    public function __construct(private readonly HtmlFetchService $htmlFetchService)
    {
    }

    public function getDecodedSearchUrl(string $author, ?string $title = null): string
    {
        $decodedUrl = self::AUTHOR_SEARCH_URL;

        if ($title !== null) {
            $decodedUrl = self::FULL_SEARCH_URL;
            $decodedUrl = str_replace('{TITLE}', $title, $decodedUrl);
        }

        return str_replace('{AUTHOR}', $author, $decodedUrl);
    }

    public function foundSearchResults(string $url): bool
    {
        $html = $this->htmlFetchService->fetchHtmlFromUrl($url);

        if ($html === false) {
            return false;
        }

        $searchResultElem = $html->find('.feedbackPanelINFO');
        $searchResultText = $searchResultElem->text()[0];

        if (str_contains($searchResultText, 'hat 0 Treffer')) {
            return false;
        }

        return true;
    }
}

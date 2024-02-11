<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;

class GoodReadsService
{
    private const BASE_URL = 'https://www.goodreads.com/review/list/{USERNAME}?page={PAGE_NUMBER}&print=true&shelf={SHELF_NAME}';

    public function __construct(
        private readonly BibService $bibService,
        private readonly HtmlFetchService $htmlFetchService
    ) {
    }

    /** @return Book[] */
    public function getIncompleteTaggedBooksFromShelf(string $user, string $shelf): array
    {
        $taggedTitles = $this->getTitlesWithTags($user, 'stadtbibliothek');
        $taggedTitles = $this->getTitlesWithTags($user, 'onleihe', $taggedTitles);

        $books = [];
        $page  = 1;

        do {
            $url  = $this->getDecodedShelfUrl($user, $shelf, $page);
            $html = $this->htmlFetchService->fetchHtmlFromUrl($url);

            if ($html === false) {
                break;
            }

            $tableBody = $html->find('#booksBody tr');

            foreach ($tableBody as $row) {
                $title        = $row->find('.title div a')->text()[0];

                if (\array_key_exists($title, $taggedTitles) && \count($taggedTitles[$title]) === 2) {
                    continue;
                }

                $imageUrl     = $row->find('.cover div div a img')[0]->getAttribute('src');
                $author       = $row->find('.author div a')[0]->text();
                $goodreadsUrl = $row->find('.title div a')[0]->getAttribute('href');
                $goodreadsUrl = 'https://www.goodreads.com'.$goodreadsUrl;

                $bibAuthorUrl = $this->bibService->getDecodedSearchUrl(urlencode($author));
                $bibFullUrl   = $this->bibService->getDecodedSearchUrl(urlencode($author), urlencode($title));

                $foundBibSearchResults = $this->bibService->foundSearchResults($bibAuthorUrl);

                if ($foundBibSearchResults === false) {
                    continue;
                }

                $tags = [];
                if (\array_key_exists($title, $taggedTitles)) {
                    $tags = $taggedTitles[$title];
                }

                $book    = new Book($title, $author, $imageUrl, $bibFullUrl, $bibAuthorUrl, $goodreadsUrl, $tags);
                $books[] = $book;
            }

            ++$page;
            $nextPageLink = $html->findOneOrFalse('a.next_page');
        } while ($nextPageLink !== false);

        return $books;
    }

    /*
     * Username has to contain the number, as you can see in the url when visiting you profile.
     * For example: 447917412-username
     */
    private function getDecodedShelfUrl(string $username, string $shelfName, int $pageNumber): string
    {
        $decodedUrl = str_replace('{USERNAME}', $username, self::BASE_URL);
        $decodedUrl = str_replace('{SHELF_NAME}', $shelfName, $decodedUrl);

        return str_replace('{PAGE_NUMBER}', \strval($pageNumber), $decodedUrl);
    }

    /**
     * @param array<string, array<int, string>>|null $titles
     *
     * @return array<string, array<int, string>>
     */
    private function getTitlesWithTags(string $user, string $tag, ?array $titles = []): array
    {
        $page  = 1;

        do {
            $url  = $this->getDecodedShelfUrl($user, $tag, $page);
            $html = $this->htmlFetchService->fetchHtmlFromUrl($url);

            if ($html === false) {
                break;
            }

            $tableBody = $html->find('#booksBody tr');

            foreach ($tableBody as $row) {
                $title            = $row->find('.title div a')->text()[0];
                $titles[$title][] = $tag;
            }

            ++$page;
            $nextPageLink = $html->findOneOrFalse('a.next_page');
        } while ($nextPageLink !== false);

        return $titles;
    }
}

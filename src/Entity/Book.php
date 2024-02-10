<?php

declare(strict_types=1);

namespace App\Entity;

class Book
{
    /**
     * @param string[]|null $tags
     */
    public function __construct(
        private string $title,
        private string $author,
        private ?string $imageUrl = null,
        private ?string $bibFullUrl = null,
        private ?string $bibAuthorUrl = null,
        private ?string $goodreadsUrl = null,
        private ?array $tags = [])
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getBibFullUrl(): ?string
    {
        return $this->bibFullUrl;
    }

    public function setBibFullUrl(?string $bibFullUrl): void
    {
        $this->bibFullUrl = $bibFullUrl;
    }

    public function getBibAuthorUrl(): ?string
    {
        return $this->bibAuthorUrl;
    }

    public function setBibAuthorUrl(?string $bibAuthorUrl): void
    {
        $this->bibAuthorUrl = $bibAuthorUrl;
    }

    public function getGoodreadsUrl(): ?string
    {
        return $this->goodreadsUrl;
    }

    public function setGoodreadsUrl(?string $goodreadsUrl): void
    {
        $this->goodreadsUrl = $goodreadsUrl;
    }

    /**
     * @return string[]
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param string[]|null $tags
     */
    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
    }
}

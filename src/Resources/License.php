<?php

namespace Helldar\PackageWizard\Resources;

use Helldar\Support\Concerns\Makeable;
use Helldar\Support\Facades\Helpers\Str;

final class License extends BaseResource
{
    use Makeable;

    protected string $license;

    protected array $authors = [];

    public function license(string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function authors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function toString(): string
    {
        return $this->parser
            ->template($this->load())
            ->replace('license', $this->getLicense())
            ->replace('year', $this->getYear())
            ->replace('authors', $this->getAuthors())
            ->get();
    }

    protected function getLicense(): string
    {
        return $this->license;
    }

    protected function getYear(): int
    {
        return date('Y');
    }

    protected function getAuthors(): string
    {
        return implode(', ', $this->authors);
    }

    protected function path(): string
    {
        $filename = Str::lower($this->license);

        return realpath(__DIR__ . '/../../resources/licenses/' . $filename);
    }
}

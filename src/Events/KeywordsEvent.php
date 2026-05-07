<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Symfony\Contracts\EventDispatcher\Event;

class KeywordsEvent extends Event
{
    public const NAME = 'plenta_casa.search.keywords';

    /**
     * @param array<string> $fields
     */
    public function __construct(
        protected array $fields = [
            'tl_pcasa_object.title',
            'tl_pcasa_object.city',
            'tl_pcasa_object.description',
            'tl_pcasa_object.equipment',
            'tl_pcasa_object.location',
            'tl_pcasa_object.other',
        ],
        protected ContentModel|null $contentModel = null,
        protected string $connector = 'AND',
        protected bool $splitKeywords = true,
    ) {
    }

    /**
     * @return array<string>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array<string> $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function getConnector(): string
    {
        return $this->connector;
    }

    public function setConnector(string $connector): void
    {
        $this->connector = $connector;
    }

    public function isSplitKeywords(): bool
    {
        return $this->splitKeywords;
    }

    public function setSplitKeywords(bool $splitKeywords): self
    {
        $this->splitKeywords = $splitKeywords;

        return $this;
    }

    public function getContentModel(): ContentModel|null
    {
        return $this->contentModel;
    }

    public function setContentModel(ContentModel|null $contentModel): self
    {
        $this->contentModel = $contentModel;

        return $this;
    }
}

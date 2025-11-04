<?php

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Symfony\Contracts\EventDispatcher\Event;

class KeywordsEvent extends Event
{
    public const NAME = 'plenta_casa.search.keywords';

    public function __construct(
        protected array $fields = ['title', 'city', 'description', 'equipment', 'location', 'other'],
        protected ?ContentModel $contentModel = null,
        protected string $connector = 'AND',
        protected bool $splitKeywords = true,
    ) {
    }

    public function getFields(): array
    {
        return $this->fields;
    }

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

    public function setSplitKeywords(bool $splitKeywords): KeywordsEvent
    {
        $this->splitKeywords = $splitKeywords;
        return $this;
    }

    public function getContentModel(): ?ContentModel
    {
        return $this->contentModel;
    }

    public function setContentModel(?ContentModel $contentModel): KeywordsEvent
    {
        $this->contentModel = $contentModel;
        return $this;
    }
}
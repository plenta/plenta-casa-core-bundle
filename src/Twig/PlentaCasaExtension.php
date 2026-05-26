<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Twig;

use Contao\FilesModel;
use Contao\StringUtil;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Attribute\AsTwigFilter;

class PlentaCasaExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        #[Autowire(param: 'plenta_casa.new_badge_days')]
        private readonly int $newBadgeDays,
    ) {
    }

    #[AsTwigFilter('casaObjectTypeName')]
    public function casaObjectTypeName(int $value, bool $withPath = false): string
    {
        $type = ObjectTypeModel::findById($value);

        if ($type) {
            return $withPath ? $this->getTitle($type) : $type->title;
        }

        return '-';
    }

    #[AsTwigFilter('casaObjectType')]
    public function casaObjectType(int $value): ObjectTypeModel
    {
        return ObjectTypeModel::findById($value);
    }

    #[AsTwigFilter('casaFormatNumber')]
    public function casaFormatNumber(int|float|string $value, int $decimals = 2): string
    {
        $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?? 'de_DE';

        $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        return $formatter->format((float) $value);
    }

    #[AsTwigFilter('casaFileMeta')]
    public function casaFileMeta(string $uuid): array
    {
        $file = FilesModel::findByUuid(StringUtil::binToUuid($uuid));

        if (null === $file) {
            return ['alt' => '', 'title' => '', 'caption' => ''];
        }

        $meta = StringUtil::deserialize($file->meta, true);
        $language = $GLOBALS['TL_LANGUAGE'] ?? 'de';

        $data = $meta[$language] ?? $meta['en'] ?? reset($meta) ?: [];

        return [
            'alt' => $data['alt'] ?? '',
            'title' => $data['title'] ?? '',
            'caption' => $data['caption'] ?? '',
        ];
    }

    protected function getTitle(ObjectTypeModel $type): string
    {
        $return = '';

        if ($type->pid) {
            $return .= $this->getTitle(ObjectTypeModel::findById($type->pid)).' > ';
        }

        return $return.$type->title;
    }

    #[AsTwigFilter('casaIsNew')]
    public function casaIsNew(int|string $createdAt): bool
    {
        $days = $this->newBadgeDays;
        $threshold = new \DateTimeImmutable("-{$days} days");

        return (new \DateTimeImmutable('@'.$createdAt)) >= $threshold;
    }
}

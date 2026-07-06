<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;

#[AsCallback(table: 'tl_pcasa_object', target: 'config.onpalette')]
class TlPcasaObjectPaletteCallback
{
    public function __invoke(string $palette, DataContainer $dc): string
    {
        $currentRecord = $dc->getCurrentRecord();

        if (null === $currentRecord) {
            return $palette;
        }

        if ('RENT' === $currentRecord['marketing_type']) {
            $fields = $this->getSubpaletteFields('marketing_type_RENT');

            $palette = PaletteManipulator::create()
                ->addLegend('price_legend', 'address_legend')
                ->addField($fields, 'price_legend', PaletteManipulator::POSITION_APPEND)
                ->applyToString($palette)
            ;
        }

        if ('BUY' === $currentRecord['marketing_type']) {
            $fields = $this->getSubpaletteFields('marketing_type_BUY');

            $palette = PaletteManipulator::create()
                ->addLegend('price_legend', 'address_legend')
                ->addField($fields, 'price_legend', PaletteManipulator::POSITION_APPEND)
                ->removeField('heatingCosts')
                ->removeField('serviceCharge')
                ->applyToString($palette)
            ;
        }

        if ($currentRecord['object_type']) {
            $objectType = ObjectTypeModel::findById((int) $currentRecord['object_type']);

            if (null !== $objectType && 'commercial' === $objectType->usageType) {
                $fields = $this->getSubpaletteFields('commercial_area');

                $palette = PaletteManipulator::create()
                    ->addLegend('commercial_area_legend', 'area_legend')
                    ->addField($fields, 'commercial_area_legend', PaletteManipulator::POSITION_APPEND)
                    ->applyToString($palette)
                ;
            }
        }

        return $palette;
    }

    /**
     * @return array<string>
     */
    private function getSubpaletteFields(string $key): array
    {
        $value = $GLOBALS['TL_DCA']['tl_pcasa_object']['casa_subpalettes'][$key] ?? '';

        return array_filter(array_map(trim(...), explode(',', (string) $value)));
    }
}

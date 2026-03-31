<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addField('addPlentaCasaCss', 'combineScripts')
    ->applyToPalette('default', 'tl_layout')
;

PaletteManipulator::create()
    ->addField('addPlentaCasaCss', 'modules')
    ->applyToPalette('modern', 'tl_layout')
;

$GLOBALS['TL_DCA']['tl_layout']['fields']['addPlentaCasaCss'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

<?php

declare(strict_types=1);

/**
 * @package       Plenta Casa
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\ArrayUtil;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Plenta\CasaCoreBundle\Model\TypeModel;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 0, [
    'plenta_casa' => [
        'plentacasa_objects' => [
            'tables' => ['tl_pcasa_object'],
        ],
        'plentacasa_import' => [
            'tables' => [],
        ],
        'plentacasa_export' => [
            'tables' => [],
        ],
        'plentacasa_config' => [
            'tables' => [
                'tl_pcasa_object_type',
                'tl_pcasa_type',
                'tl_pcasa_targetgroup',
                'tl_pcasa_quarter',
            ],
        ],
        'plentacasa_support' => [
            'tables' => [],
        ],
    ],
]);

$GLOBALS['PLENTA']['CASA']['config'] = [
    'tl_pcasa_object_type',
    'tl_pcasa_targetgroup',
    'tl_pcasa_quarter',
];

// Kauf/Miete
$GLOBALS['PLENTA']['CASA']['marketing_type'] = ['RENT', 'BUY'];

// Wohnen/Gewerbe/Anlage
$GLOBALS['PLENTA']['CASA']['object_type'] = ['LIVING', 'COMMERCIAL', 'INVESTMENT'];

$GLOBALS['PLENTA']['CASA']['type'] = [
    'APARTMENT',
    'HOUSE',
    'TRADE_SITE',
    'GARAGE',
    'SHORT_TERM_ACCOMODATION',
    'OFFICE',
    'GASTRONOMY',
    'STORE',
    'INDUSTRY',
    'SPECIAL_PURPOSE',
    'INVESTMENT',
];

$GLOBALS['TL_MODELS'][ObjectModel::getTable()] = ObjectModel::class;
$GLOBALS['TL_MODELS'][TypeModel::getTable()] = TypeModel::class;
$GLOBALS['TL_MODELS'][ObjectTypeModel::getTable()] = ObjectTypeModel::class;

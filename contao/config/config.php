<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\ArrayUtil;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 0, [
    'plenta_casa' => [
        'plentacasa_objects' => [
            'tables' => ['tl_pcasa_object'],
        ],
        'plentacasa_projects' => [
            'tables' => ['tl_pcasa_project'],
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

$GLOBALS['PLENTA']['CASA']['sorting_type'] = [
    'order_date_asc',
    'order_date_desc',
    'order_headline_asc',
    'order_headline_desc',
    'order_random',
];

$GLOBALS['TL_MODELS'][ObjectModel::getTable()] = ObjectModel::class;
$GLOBALS['TL_MODELS'][ObjectTypeModel::getTable()] = ObjectTypeModel::class;

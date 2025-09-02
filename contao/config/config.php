<?php

use Contao\ArrayUtil;
use Plenta\CasaCoreBundle\Model\ObjectModel;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 0, [
    'plenta_casa' => [
        'plentacasa_objects' => [
            'tables' => ['tl_pcasa_object'],
        ],
        'plentacasa_config' => [
            'tables' => ['tl_pcasa_object_type', 'tl_pcasa_type'],
        ],
        'plentacasa_support' => [
            'tables' => [],
        ],
    ],
]);

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
    'INVESTMENT'
];

$GLOBALS['TL_MODELS'][ObjectModel::getTable()] = ObjectModel::class;

<?php

declare(strict_types=1);

/**
 * @package       PlentaCasa Core
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_pcasa_type'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_pcasa_object_type',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'tstamp' => 'index',
            ]
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['title'],
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s'
        ]
    ],
    'palettes' => [
        'default' => 'title',
    ],
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'foreignKey' => 'tl_pcasa_object_type.title',
            'relation' => ['type' => 'belongsTo', 'load' => 'eager'],
            'sql' => "int(10) unsigned NOT NULL default 0"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default 0"
        ],
        'title' => [
            'inputType' => 'text',
            'sql' => "varchar(255) NOT NULL default ''",
        ],
    ],
];

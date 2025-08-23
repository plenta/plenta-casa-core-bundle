<?php

use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_pcasa_city'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_TREE,
            'rootPaste' => true,
            'showRootTrails' => true,
            'icon' => 'pagemounts.svg',
            'panelLayout' => 'filter;search',
            'defaultSearchField' => 'title'
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
    ],

    'palettes' => [
        'default' => 'title',
    ],

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default 0"
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default 0"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'inputType' => 'text',
            'search' => true,
            'sorting' => true,
            'exclude' => true,
            'eval' => ['mandatory' => true, 'maxlength' => 255],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
    ],
];

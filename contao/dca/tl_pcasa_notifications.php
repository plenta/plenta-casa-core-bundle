<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\DataContainer;
use Contao\DC_Table;
use Plenta\CasaCoreBundle\Controller\Contao\Backend\ConfigController;

$GLOBALS['TL_DCA']['tl_pcasa_notifications'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['title'],
            'panelLayout' => 'sort,search',
            'defaultSearchField' => 'title',
            'disableGrouping' => true,
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'global_operations' => [
            'back' => [
                'route' => ConfigController::class,
                'label' => &$GLOBALS['TL_LANG']['MSC']['backBT'],
                'icon' => 'back.svg',
            ],
        ],
    ],

    'palettes' => [
        'default' => 'title',
    ],

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'inputType' => 'text',
            'search' => true,
            'sorting' => true,
            'exclude' => true,
            'flag' => DataContainer::SORT_INITIAL_LETTER_BOTH,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
    ],
];

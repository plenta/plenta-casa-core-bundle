<?php

use Contao\BackendUser;
use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_pcasa_object'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'tstamp' => 'index',
            ]
        ]
    ],

    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => [
                'marketing_type',
                'object_type',
                'type',
                'city',
                'street',
            ],
            'panelLayout' => 'filter;sort,search,limit',
            'defaultSearchField' => 'title',
        ],
        'label' => [
            'fields' => ['title', 'city', 'district'],
            'format' => '%s, %s (%s)',
        ]
    ],

    'palettes' => [
        'default' => '{master_data_legend},marketing_type,object_type,object_kind,type,title,unit_id;
        {address_legend},street,housenumber,postal,city,district,country,coordinates_lat,coordinates_lng,hideStreet;
        {description_legend},;
        {media_legend};
        {author_legend:hide},author;
        {publish_legend},published,start,stop',
    ],

    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'flag' => DataContainer::SORT_DAY_DESC,
            'sql' => "int(10) unsigned NOT NULL default 0"
        ],

        'marketing_type' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            //'options' => $GLOBALS['PLENTA']['CASA']['marketing_type'],
            'eval' => [
                'mandatory' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w50'
            ],
            'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''",
        ],
        'object_type' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            //'options' => $GLOBALS['PLENTA']['CASA']['object_type'],
            'eval' => [
                'mandatory' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w50'
            ],
            'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''",
        ],
        'object_kind' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            //'options' => $GLOBALS['PLENTA']['CASA']['object_type'],
            'eval' => [
                'mandatory' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w50'
            ],
            'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''",
        ],
        'type' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            //'options' => $GLOBALS['PLENTA']['CASA']['type'],
            'eval' => [
                'mandatory' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w50'
            ],
            'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''",
        ],

        'title' => [
            'inputType' => 'text',
            'search' => true,
            'exclude' => true,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'long', 'doNotCopy' => true],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'unit_id' => [
            'inputType' => 'text',
            'search' => true,
            'sorting' => true,
            'exclude' => true,
            'eval' => [
                'maxlength' => 128,
                'unique' => true,
                'tl_class' => 'w50'
            ],
            'sql' => "varchar(128) NOT NULL default ''"
        ],

        'street' => [
            'inputType' => 'text',
            'filter' => true,
            'search' => true,
            'exclude' => true,
            'flag' => 11,
            'eval' => ['maxlength' => 128, 'mandatory' => true, 'tl_class' => 'w33'],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'housenumber' => [
            'inputType' => 'text',
            'exclude' => true,
            'eval' => ['maxlength' => 8, 'rgxp' => 'alnum ', 'tl_class' => 'w33'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'postal' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 32, 'mandatory' => true, 'tl_class' => 'w33'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'city' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) unsigned NOT NULL default ''",
        ],
        'district' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) unsigned NOT NULL default ''",
        ],
        'coordinates_lat' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) unsigned NOT NULL default ''",
        ],
        'coordinates_lng' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) unsigned NOT NULL default ''",
        ],
        'hideStreet' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],

        'author' => [
            'default' => static fn () => BackendUser::getInstance()->id,
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_user.name',
            'eval' => [
                'doNotCopy' => true,
                'mandatory' => true,
                'chosen' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w50',
            ],
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation'  => ['type' => 'hasOne', 'load' => 'eager'],
        ],

        'published' => [
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy'=>true],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'start' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'country' => [
            'inputType' => 'select',
            'options_callback' => fn () => \Contao\System::getContainer()->get('contao.intl.countries')->getCountries(),
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'includeBlankOption' => true],
            'sql' => "varchar(2) NOT NULL default ''",
        ],
    ],
];

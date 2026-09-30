<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_listing'] = '{type_legend},title,headline,type;{config_legend},perPage,numberOfItems,plenta_casa_preFilter,plenta_casa_sorting,size;{redirect_legend},jumpTo,linkTitle,titleText;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_filter'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_filterElements,plenta_casa_filterAddResetButton;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_reader'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_readerParts,size,fullsize;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'plenta_casa_preFilter';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['plenta_casa_preFilter'] = 'plenta_casa_preFilterMarketingType,plenta_casa_preFilterType,plenta_casa_preFilterLocation,plenta_casa_preFilterDistrict,plenta_casa_preFilterQuarter,plenta_casa_preFilterTargetGroup,plenta_casa_preFilterRooms';

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_readerParts'] = [
    'inputType' => 'group',
    'palette' => ['plenta_casa_readerPartType', 'headline', 'plenta_casa_readerPartTpl'],
    'fields' => [
        '&headline' => [
            'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'],
        ],
    ],
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddObjectType'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddType'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddKeyword'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddMarketingType'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_readerPartType'] = [
    'inputType' => 'select',
    'eval' => ['chosen' => true, 'includeBlankOption' => true, 'submitOnChange' => true],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['plenta_casa_readerPartType_options'],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_readerPartTpl'] = [
    'inputType' => 'select',
    'eval' => ['chosen' => true, 'tl_class' => 'clr'],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddArea'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddRooms'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddPrice'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterAddResetButton'] = [
    'inputType' => 'checkbox',
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_sorting'] = [
    'inputType' => 'select',
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['plenta_casa_sorting_options'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default 'order_date_desc'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilter'] = [
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterMarketingType'] = [
    'inputType' => 'select',
    'options' => $GLOBALS['PLENTA']['CASA']['marketing_type'],
    'reference' => &$GLOBALS['TL_LANG']['PLENTA']['CASA']['marketing_types'],
    'eval' => ['tl_class' => 'w50', 'multiple' => true, 'chosen' => true],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterType'] = [
    'inputType' => 'select',
    'foreignKey' => 'tl_pcasa_object_type.title',
    'eval' => ['tl_class' => 'w50', 'multiple' => true, 'chosen' => true],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterLocation'] = [
    'inputType' => 'select',
    'eval' => ['tl_class' => 'w50', 'multiple' => true, 'chosen' => true],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterDistrict'] = [
    'inputType' => 'select',
    'eval' => ['tl_class' => 'w50', 'multiple' => true, 'chosen' => true],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterQuarter'] = [
    'inputType' => 'select',
    'foreignKey' => 'tl_pcasa_quarter.title',
    'eval' => ['tl_class' => 'w50', 'multiple' => true, 'chosen' => true],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterTargetGroup'] = [
    'inputType' => 'select',
    'foreignKey' => 'tl_pcasa_targetgroup.title',
    'eval' => ['tl_class' => 'w50', 'multiple' => true, 'chosen' => true],
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_preFilterRooms'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'rgxp' => 'digit'],
    'sql' => "varchar(8) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterElements'] = [
    'inputType' => 'checkboxWizard',
    'options' => [
        'marketingType',
        'objectType',
        'keyword',
        'area',
        'rooms',
        'price',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['plenta_casa_filterElementsRef'],
    'sql' => 'mediumtext NULL',
];

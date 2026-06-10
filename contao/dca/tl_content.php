<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_listing'] = '{type_legend},title,headline,type;{config_legend},perPage,numberOfItems,plenta_casa_sorting,size;{redirect_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_filter'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_filterAddMarketingType,plenta_casa_filterAddObjectType,plenta_casa_filterAddType,plenta_casa_filterAddKeyword,plenta_casa_filterAddArea,plenta_casa_filterAddRooms,plenta_casa_filterAddPrice,plenta_casa_filterAddResetButton;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_reader'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_readerParts,size,fullsize;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

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

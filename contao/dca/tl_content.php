<?php

use Plenta\CasaCoreBundle\Controller\Contao\ContentElement\Reader;
use Plenta\CasaCoreBundle\Controller\Contao\ContentElement\Listing;

$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_listing'] = '{type_legend},title,headline,type;{config_legend},perPage,numberOfItems,size;{redirect_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_filter'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_filterAddMarketingType,plenta_casa_filterAddObjectType,plenta_casa_filterAddType,plenta_casa_filterAddKeyword,plenta_casa_filterAddArea,plenta_casa_filterAddRooms,plenta_casa_filterAddPrice;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_reader'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_readerParts,size,fullsize;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_readerParts'] = [
    'inputType' => 'group',
    'palette' => ['plenta_casa_readerPartType', 'headline', 'plenta_casa_readerPartTpl'],
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
    'sql' => "mediumtext NULL",
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
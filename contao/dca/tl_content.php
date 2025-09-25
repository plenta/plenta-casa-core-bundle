<?php

use Plenta\CasaCoreBundle\Controller\Contao\ContentElement\Reader;
use Plenta\CasaCoreBundle\Controller\Contao\ContentElement\Listing;

$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_listing'] = '{type_legend},title,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_filter'] = '{type_legend},title,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['plenta_casa_reader'] = '{type_legend},title,headline,type;{config_legend},plenta_casa_readerParts,size,fullsize;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_readerParts'] = [
    'inputType' => 'checkboxWizard',
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
    'sql' => "mediumtext NULL",
];

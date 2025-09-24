<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\BackendUser;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\System;

$GLOBALS['TL_DCA']['tl_pcasa_object'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'tstamp' => 'index',
            ],
        ],
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
        ],
    ],

    'palettes' => [
        'default' => '{master_data_legend},marketing_type,object_type,type,title,unit_id,alias;
        {address_legend},street,housenumber,postal,city,district,country,coordinates_lat,coordinates_lng,hideStreet;
        {price_legend},baseRent,totalRent,heatingCosts,serviceCharge,heatingCostsInSc,houseServiceCharge,nonRecoverableCosts,maintenanceReserve,deposit,parkingSpacePrice;
        {area_legend},livingArea,plotArea,rooms,bedrooms,bathrooms,floorArea,balconyArea,gardenArea,floor,numberOfFloors,floorPosition,numberOfParkingSpaces,parkingSpaceType;
        {facilities_legend},facilities,bathroomFacilities,flooring;
        {description_legend},description,location,equipment,other;
        {condition_legend},propertyCondition,lastRefurbishment,qualityOfEquipment;
        {energy_certificate_legend},energyCertificate,creationDate,issueDate,validThrough,energyCertificateType,energyEfficiencyClass,energyRating,energyRatingElectricity,energyRatingHeating,co2Emission,hotWaterIncluded,heatingType,primaryEnergySource,yearOfConstruction,yearOfConstructionEngineering,yearOfConstructionUnknown;
        {media_legend};
        {marketing_legend:hide},targetgroups,quarter;
        {author_legend:hide},author;
        {publish_legend},published,start,stop',
    ],

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'flag' => DataContainer::SORT_DAY_DESC,
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],

        'marketing_type' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            'options' => $GLOBALS['PLENTA']['CASA']['marketing_type'],
            'reference' => &$GLOBALS['TL_LANG']['PLENTA']['CASA']['marketing_types'],
            'eval' => [
                'mandatory' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w33',
            ],
            'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''",
        ],
        'object_type' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_pcasa_object_type.title',
            'eval' => [
                'mandatory' => true,
                'submitOnChange' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w33',
            ],
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],
        'type' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_pcasa_type.title',
            'eval' => [
                'mandatory' => true,
                'includeBlankOption' => true,
                'tl_class' => 'w33',
            ],
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],

        'title' => [
            'inputType' => 'text',
            'search' => true,
            'exclude' => true,
            'eval' => ['maxlength' => 255, 'tl_class' => 'long', 'doNotCopy' => true],
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
                'tl_class' => 'w50',
            ],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'alias' => [
            'search' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'folderalias', 'doNotCopy' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) BINARY NOT NULL default ''",
        ],

        'street' => [
            'inputType' => 'text',
            'filter' => true,
            'search' => true,
            'exclude' => true,
            'flag' => 11,
            'eval' => ['maxlength' => 128, 'mandatory' => true, 'tl_class' => 'w25'],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'housenumber' => [
            'inputType' => 'text',
            'exclude' => true,
            'eval' => ['maxlength' => 8, 'rgxp' => 'alnum ', 'tl_class' => 'w25'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'postal' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 32, 'mandatory' => true, 'tl_class' => 'w25'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'city' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w25'],
            'sql' => "varchar(255) unsigned NOT NULL default ''",
        ],
        'district' => [
            'filter' => true,
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) unsigned NOT NULL default ''",
        ],
        'country' => [
            'inputType' => 'select',
            'default' => 'DE',
            'options_callback' => fn () => System::getContainer()->get('contao.intl.countries')->getCountries(),
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'includeBlankOption' => true],
            'sql' => "varchar(2) NOT NULL default ''",
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
            'relation' => ['type' => 'hasOne', 'load' => 'eager'],
        ],

        'targetgroups' => [
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'foreignKey' => 'tl_pcasa_targetgroup.title',
            'eval' => ['multiple' => true, 'tl_class' => 'w50'],
            'sql' => 'blob NULL',
        ],
        'quarter' => [
            'inputType' => 'select',
            'exclude' => true,
            'filter' => true,
            'foreignKey' => 'tl_pcasa_quarter.title',
            'eval' => ['multiple' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],

        'published' => [
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true],
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

        'price' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => 'mediumint NOT NULL default 0',
        ],
        'doNotShowPrice' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'baseRent' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'clr w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'totalRent' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'heatingCosts' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'serviceCharge' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'heatingCostsInSc' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'houseServiceCharge' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'deposit' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w25'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'nonRecoverableCosts' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'maintenanceReserve' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w25'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'parkingSpacePrice' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w25', 'rgxp' => 'digit'],
            'sql' => 'int(10) NOT NULL default 0',
        ],
        'monthlyRentalIncome' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w25', 'rgxp' => 'digit'],
            'sql' => 'mediumint NOT NULL default 0',
        ],
        'livingArea' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'rgxp' => 'digit'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'plotArea' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'rgxp' => 'digit'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'rooms' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'bedrooms' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'bathrooms' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'floorArea' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'balconyArea' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'gardenArea' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'floor' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'numberOfFloors' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'floorPosition' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w33'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'paymentInterval' => [
            'inputType' => 'select',
            'options' => ['daily', 'weekly', 'monthly'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'availableFrom' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'availableTo' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'minimumRentalPeriod' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'maximumRentalPeriod' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'maximumPersons' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'gender' => [
            'inputType' => 'select',
            'options' => ['male', 'female', 'any'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'furnished' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'facilities' => [
            'inputType' => 'checkbox',
            'options' => [
                'lift',
                'cellar',
                'fittedKitchen',
                'loggia',
                'listedBuilding',
                'storageRoom',
                'swimmingPool',
                'alarmSystem',
                'airConditioning',
                'accessible',
                'guestWc',
                'balcony',
                'garden',
                'holidayHome',
                'fireplace',
                'sauna',
                'conservatory',
                'flatShare',
                'nonSmokersOnly',
            ],
            'eval' => ['multiple' => true, 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
        'bathroomFacilities' => [
            'inputType' => 'checkbox',
            'options' => [
                'shower',
                'tub',
                'window',
                'bidet',
                'urinal',
            ],
            'eval' => ['multiple' => true, 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
        'petsAllowed' => [
            'inputType' => 'select',
            'options' => ['yes', 'no', 'negotiable'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'flooring' => [
            'inputType' => 'checkbox',
            'options' => ['concrete', 'epoxy', 'tile', 'plank', 'laminate', 'parquet', 'pvc', 'carpet', 'antistaticCarpet', 'carpetTiles', 'stone', 'linoleum', 'marble', 'terracotta', 'granite', 'vinyl', 'customersOption', 'none'],
            'eval' => ['tl_class' => 'clr', 'multiple' => true],
            'sql' => "mediumtext NULL",
        ],
        'description' => [
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
        'location' => [
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
        'equipment' => [
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
        'other' => [
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
        'let' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'propertyCondition' => [
            'inputType' => 'select',
            'options' => ['firstTimeUse', 'firstTimeUseAfterRefurbishment', 'mintCondition', 'refurbished', 'partlyRefurbished', 'inNeedOfRefurbishment', 'decrepit', 'modernized', 'completelyRenovated', 'partlyRenovated', 'neat', 'inNeedOfRenovation', 'customersOption', 'dilapidated'],
            'sql' => "varchar(48) NOT NULL default ''",
        ],
        'lastRefurbishment' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'qualityOfEquipment' => [
            'inputType' => 'select',
            'options' => ['luxurious', 'upscale', 'normal', 'plain'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'numberOfParkingSpaces' => [
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'rgxp' => 'digit'],
            'sql' => "varchar(8) NOT NULL default ''",
        ],
        'parkingSpaceType' => [
            'inputType' => 'select',
            'options' => ['garage', 'outside', 'carport', 'duplex', 'carPark', 'undergroundCarPark', 'doubleGarage'],
            'sql' => "varchar(32 NOT NULL default ''",
        ],
        'energyCertificate' => [
            'inputType' => 'select',
            'options' => ['yes', 'no', 'availableAtViewing'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'creationDate' => [
            'inputType' => 'select',
            'options' => ['beforeMay2014', 'fromMay2014'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'issueDate' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'validThrough' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'energyCertificateType' => [
            'inputType' => 'select',
            'options' => ['usageCertificate', 'demandCertificate'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'energyEfficiencyClass' => [
            'inputType' => 'select',
            'options' => ['A+', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(4) NOT NULL default ''",
        ],
        'energyRating' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'energyRatingElectricity' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'energyRatingHeating' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'co2Emission' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'hotWaterIncluded' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'heatingType' => [
            'inputType' => 'select',
            'options' => ['selfContainedCentralHeating', 'stoveHeating', 'centralHeating', 'districtHeatingCentralPlant', 'electricHeating', 'districtHeating', 'floorHeating', 'gasHeating', 'woodPelletHeating', 'nightStorageHeating', 'oilHeating', 'solarHeating', 'heatPump'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(48) NOT NULL default ''",
        ],
        'primaryEnergySource' => [
            'inputType' => 'select',
            'options' => ['geothermalEnergy', 'solar', 'woodPellets', 'gas', 'oil', 'longDistanceHeat', 'electricity', 'coal', 'heavyNaturalGas', 'lightNaturalGas', 'liquidGas', 'longDistanceHeatSteam', 'airHeatPump', 'wood', 'woodChips', 'coalCoke', 'shortDistanceHeat', 'heatDistribution', 'bioEnergy', 'windEnergy', 'hydroEnergy', 'ambientHeat', 'chpFossil', 'chpRenewable', 'chpRegenerative', 'chpOrganic'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'yearOfConstruction' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'yearOfConstructionEngineering' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'yearOfConstructionUnknown' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
    ],
];

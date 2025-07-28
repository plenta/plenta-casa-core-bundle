<?php

use Contao\ArrayUtil;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 0, [
    'plenta_casa' => [
        'plcasa_objects' => [
            'tables' => [],
        ],
        'plcasa_config' => [
            'tables' => [],
        ],
        'plcasa_support' => [
            'tables' => [],
        ],
    ],
]);
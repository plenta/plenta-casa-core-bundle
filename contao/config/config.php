<?php

use Contao\ArrayUtil;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 0, [
    'plenta_casa' => [
        'plentacasa_objects' => [
            'tables' => [],
        ],
        'plentacasa_config' => [
            'tables' => [],
        ],
        'plentacasa_support' => [
            'tables' => [],
        ],
    ],
]);
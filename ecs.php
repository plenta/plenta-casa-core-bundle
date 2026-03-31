<?php

declare(strict_types=1);

use Contao\EasyCodingStandard\Set\SetList;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $date = date('Y');

    $header = <<<EOF
    @package       Plenta Casa Core Bundle
    @copyright     Copyright (c) $date, Plenta.io
    @author        Plenta.io <https://plenta.io>
    @license       commercial
    EOF;

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, [
        'header' => $header,
    ]);
};


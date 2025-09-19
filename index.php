<?php // site\plugins\heineref_scssphp\index.php

/**
 * SCSS Snippets
 *
 * are with this file part of the plugin
 *
 * @author    HeinerEF
 * @license   https://github.com/HeinerEF/kirby-scssphp/blob/main/LICENSE.md
 * @link      https://github.com/HeinerEF/kirby-scssphp
 *
 * @update    2025-09-14 by HeinerEF (add snippet 'scss.critical.php')
 * @update    2025-09-12 by HeinerEF (add 'vendor/autoload.php', needed or not)
 * @update    2025-08-09 by HeinerEF (update to Kirby 5.x)
 * @created   2020-01-05 by HeinerEF (make snippet 'scss.php' part of the plugin)
 */

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('heineref/scssphp', [
  'snippets' => [ // look also at: "/site/snippets", which overwrite the following files
    'scss'          => __DIR__ . '/snippets/scss.php',
    'scss.critical' => __DIR__ . '/snippets/scss.critical.php',
  ],
]);

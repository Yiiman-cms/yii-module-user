<?php
/**
 * Site: http://yiiman.ir
 * AuthorName: gholamreza beheshtian
 * AuthorNumber:09353466620
 * AuthorCompany: YiiMan
 *
 *
 */

use yii\base\Event;
use yii\web\Application;

$dir = basename(__DIR__);


$conf =
    [
        'name' => $dir,
        'type' => ['common'],
        'namespace' => 'system\modules\\' . $dir,
        'address' => '',
    ];

return $conf;

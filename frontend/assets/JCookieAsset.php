<?php
/**
 * Created by PhpStorm.
 * User: Insolita
 * Date: 11.11.14
 * Time: 0:50
 */

namespace frontend\assets;

use \yii\web\AssetBundle;
class JCookieAsset  extends AssetBundle{
    public $sourcePath = '@vendor/bower/jquery-cookie';

    public $js
        = [
            'jquery.cookie.js',
        ];

    public $depends=[
        'yii\web\YiiAsset'
    ];
}

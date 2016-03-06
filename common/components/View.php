<?php
namespace common\components;
;
/*
* This file is part of
*     the yii2   _
*  _ __ ___   __| |_ __   __ _  __ _  ___  ___
* | '_ ` _ \ / _` | '_ \ / _` |/ _` |/ _ \/ __|
* | | | | | | (_| | |_) | (_| | (_| |  __/\__ \
* |_| |_| |_|\__,_| .__/ \__,_|\__, |\___||___/
*                 |_|          |___/
*                 module
*
*	Copyright (c) 2016 Jacob Moen
*	Licensed under the MIT license
*/
use Yii;
use yii\base\InvalidCallException;

/**
 * This View class overrides render and findViewFile
 * to use the theme view files
 */
class View extends \yii\web\View {

    /**
     * [render description]
     * @param  [type] $view   [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function render($view, $params = array(), $context = null)
    {
        if ($context === null) {
            $context = $this->context;
        }

        return parent::render($view, $params, $context);
    }

    /**
     * [findViewFile description]
     * @param  [type] $view    [description]
     * @param  [type] $context [description]
     * @return [type]          [description]
     */
    protected function findViewFile($view, $context = null)
    {
        $path = $view . '.' . $this->defaultExtension;
        if ($this->theme !== null) {
            $path = $this->theme->applyTo($path);
        }
        $viewfile = parent::findViewFile($path, $context);

        return $viewfile;
    }

}

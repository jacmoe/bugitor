<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2013 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

Yii::import('bootstrap.widgets.TbMenu');

class BugitorMenu extends TbMenu
{

    /**
     * Checks whether a menu item is active.
     * This is done by checking if the currently requested URL is generated by the 'url' option
     * of the menu item. Note that the GET parameters not specified in the 'url' option will be ignored.
     * @param array $item the menu item to be checked
     * @param string $route the route of the current request
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item, $route) {
        if (isset($item['id'])) {
            if($route === $item['id'])
                return true;
            if(($route === 'changeset/view')&&($item['id'] === 'project/code'))
                return true;
            if(($route === 'changeset/index')&&($item['id'] === 'project/code'))
                return true;
            if(($route === 'milestone/view')&&($item['id'] === 'project/roadmap'))
                return true;
            if(($route === 'issue/view')&&($item['id'] === 'issue/index'))
                return true;
            if(($route === 'issue/update')&&($item['id'] === 'issue/index'))
                return true;
            if(($route === 'project/admin')&&($item['id'] === 'project/admin'))
                return true;
            if(isset(Yii::app()->controller->module)){
                if((Yii::app()->controller->module->id === 'rights')&&($item['id'] === 'rights'))
                    return true;
            }
            if(isset(Yii::app()->controller->module)){
                if((Yii::app()->controller->module->id === 'user')&&($item['id'] === 'user'))
                    return true;
            }
            if((Yii::app()->controller->id === 'milestone')&&($item['id'] === 'project/settings')) {
                if(!($route === 'milestone/view'))
                    return true;
            }
            if((Yii::app()->controller->id === 'issueCategory')&&($item['id'] === 'project/settings')) {
                return true;
            }
            if((Yii::app()->controller->id === 'member')&&($item['id'] === 'project/settings')) {
                return true;
            }
            if((Yii::app()->controller->id === 'repository')&&($item['id'] === 'project/settings')) {
                return true;
            }
            if((Yii::app()->controller->id === 'projectLink')&&($item['id'] === 'project/settings')) {
                return true;
            }
            return false;
        }
        return false;
    }

}

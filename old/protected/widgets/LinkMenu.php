<?php
/*/////////////////////////////////////////////////////////////////////////
// This file is part of
//      _
//     | | __ _  ___ _ __ ___   ___   ___  ___
//  _  | |/ _` |/ __| '_ ` _ \ / _ \ / _ \/ __|
// | |_| | (_| | (__| | | | | | (_) |  __/\__ \
//  \___/ \__,_|\___|_| |_| |_|\___/ \___||___/
//                                             personal blogging software
// Copyright (c) 2010 by Jacob 'jacmoe' Moen
// License: GNU General Public License (GPL) - see root_dir/license.txt for details
// Credits: see root_dir/credits.txt
// Homepage: http://www.jacmoe.dk/page/jacmoes
// Repository: http://bitbucket.org/jacmoe/jacmoes
/////////////////////////////////////////////////////////////////////////*/
?>
<?php
  
class LinkMenu extends BugitorMenu
{
    public function init()
    {
        $this->items = $this->getMenu();
        parent::init();
    }
    
    private function getMenu()
    {
        $criteria = new CDbCriteria(array(
                    'order' => 'position ASC',
                ));
        if(isset($_GET['identifier'])) {
            $criteria->condition = 'project_id = :project_id';
            $criteria->params = array('project_id' => Project::getProjectIdFromIdentifier($_GET['identifier']));
        }

        $menu = array();
        
        $menu[] = array('label'=>'Home','url'=>array('/projects/'));
        $menu[] = array('label'=>'Administration','url'=>array('/admin'), 'visible' => Yii::app()->user->checkAccess(Rights::module()->superuserName));
        
        if(isset($_GET['identifier'])) {
            $items = ProjectLink::model()->findAll($criteria);
            if($items) {
                $menu[] = array('label'=>'|','url'=>array());
            }
            foreach ($items as $item)
            {
                $menu[] = array('label'=>$item->title,'url'=> $item->url, 'linkOptions' => array('title' => $item->description));
            }
        }
        return $menu;
    }
}
  
?>

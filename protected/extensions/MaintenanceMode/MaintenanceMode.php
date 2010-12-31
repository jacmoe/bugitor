<?php
/**
 * Maintenance mode for Yii framework.
 * @author Karagodin Evgeniy (ekaragodin@gmail.com)
 * v 1.0
 */
class MaintenanceMode extends CComponent {

    public $enabledMode = true;
    public $capUrl = 'maintenance/index';
    public $message = "This site is undergoing maintenance.<br/>Please check back later.";

    public $users = array('jacmoe',);
    public $roles = array('Administrator',);

    public $urls = array();

    public function init() {

        if ($this->enabledMode) {

            $disable = in_array(Yii::app()->user->name, $this->users);
            foreach ($this->roles as $role) {
                $disable = $disable || Yii::app()->user->checkAccess($role);
            }

            $disable = $disable || in_array(Yii::app()->request->url, $this->urls);

            if (!$disable) {
                if ($this->capUrl === 'maintenance/index') {
                    Yii::app()->controllerMap['maintenance'] = 'application.extensions.MaintenanceMode.MaintenanceController';
                }

                Yii::app()->catchAllRequest = array($this->capUrl);
            }
        }

    }

}

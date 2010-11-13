<?php

class DefaultController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

}
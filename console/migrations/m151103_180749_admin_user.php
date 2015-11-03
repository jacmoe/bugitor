<?php

use yii\db\Schema;
use yii\db\Migration;

class m151103_180749_admin_user extends Migration
{
    public function up()
    {
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }

      $this->insert('user', array(
          "id" => "2",
          "username" => "admin",
          "status" => 10,
          "auth_key" => Yii::$app->security->generateRandomString(),
          "password_hash" => Yii::$app->security->generatePasswordHash('admin'),
          "created_at"=>time(),
          "updated_at"=>time(),
      ));
    }

    public function down()
    {
      $this->delete('user', array('id' => 2));
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

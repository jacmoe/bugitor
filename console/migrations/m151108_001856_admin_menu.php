<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_001856_admin_menu extends Migration
{
    public function up()
    {
      $menuTable = "{{%menu}}";
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
      }

      $this->createTable($menuTable, [
          'id' => Schema::TYPE_PK,
          'name' => Schema::TYPE_STRING . '(128) NOT NULL',
          'parent' => Schema::TYPE_INTEGER. ' NULL',
          'route' => Schema::TYPE_STRING . '(256)',
          'order' => Schema::TYPE_INTEGER,
          'data' => Schema::TYPE_TEXT,
          "FOREIGN KEY (parent) REFERENCES {$menuTable}(id) ON DELETE SET NULL ON UPDATE CASCADE",
      ], $tableOptions);
    }

    public function down()
    {
      $this->dropTable(Configs::menuTable());
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

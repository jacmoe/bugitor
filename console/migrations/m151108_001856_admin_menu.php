<?php

use yii\db\Schema;
use yii\db\Migration;


// Installs the menu table into the database
// to be used by the mdm Rbac Admin module
class m151108_001856_admin_menu extends Migration
{
    public function safeUp()
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

    public function safeDown()
    {
      $this->dropTable("{{%menu}}");
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

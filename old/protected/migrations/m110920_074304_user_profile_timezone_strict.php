<?php

class m110920_074304_user_profile_timezone_strict extends CDbMigration {

    public function up() {
        $this->alterColumn("{{profiles}}", "timezone", "varchar(65) NOT NULL DEFAULT 'Europe/London'");
    }

    public function down() {
        echo "m110920_074304_user_profile_timezone_strict does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
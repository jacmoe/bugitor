<?php

class m110518_075339_changeset_children_parents extends CDbMigration
{
    public function up()
    {
            $this->addColumn("{{changeset}}",
                    "branch_count",
                    "integer NOT NULL DEFAULT '0'");
            $this->addColumn("{{changeset}}",
                    "tag_count",
                    "integer NOT NULL DEFAULT '0'");
            $this->addColumn("{{changeset}}",
                    "parent_count",
                    "integer NOT NULL DEFAULT '0'");
            $this->addColumn("{{changeset}}",
                    "child_count",
                    "integer NOT NULL DEFAULT '0'");
            $this->addColumn("{{changeset}}",
                    "children",
                    "string DEFAULT NULL");
            $this->renameColumn("{{changeset}}",
                    "parent",
                    "parents");
            $this->renameColumn("{{changeset}}",
                    "branch",
                    "branches");
    }

    public function down()
    {
        echo "m110518_075339_changeset_children_parents does not support migration down.\n";
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

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_015552_changeset_parents extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%changeset}}",
            "branch_count",
            "integer NOT NULL DEFAULT '0'");
        $this->addColumn("{{%changeset}}",
            "tag_count",
            "integer NOT NULL DEFAULT '0'");
        $this->addColumn("{{%changeset}}",
            "parent_count",
            "integer NOT NULL DEFAULT '0'");
        $this->renameColumn("{{%changeset}}",
            "parent",
            "parents");
        $this->renameColumn("{{%changeset}}",
            "branch",
            "branches");
    }

    public function safeDown()
    {
        $this->dropColumn("{{%changeset}}",
            "branch_count");
        $this->dropColumn("{{%changeset}}",
            "tag_count");
        $this->dropColumn("{{%changeset}}",
            "parent_count");
        $this->renameColumn("{{%changeset}}",
            "parents",
            "parent");
        $this->renameColumn("{{%changeset}}",
            "branches",
            "branch");
    }
}

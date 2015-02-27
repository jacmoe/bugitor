<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_020054_pending_changeset extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%pending_changeset}}', [
            'id' => Schema::TYPE_PK,
            'changeset_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);

        $this->createIndex('fk_pending_changeset_id', '{{%pending_changeset}}', 'changeset_id');

        $this->addForeignKey('fk_pending_changeset_id',
            '{{%pending_changeset}}',
            'changeset_id',
            '{{%changeset}}', 'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_pending_changeset_id', '{{%pending_changeset}}');
        $this->dropIndex('fk_pending_changeset_id', '{{%pending_changeset}}');
        $this->dropTable('{{%pending_changeset}}');
    }
}

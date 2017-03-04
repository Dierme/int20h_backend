<?php

use yii\db\Migration;

class m170304_115747_vkuser_groups extends Migration
{
    private $tableName = '{{%vkuser_has_vkgroups}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'vk_profile_id' => $this->integer(11)->notNull(),
            'vk_group_id' => $this->integer(11)->notNull(),
        ]);

        $this->addForeignKey('#FK_vkuser_has_groups_vk_profile', $this->tableName, 'vk_profile_id', 'vk_profile', 'id');
        $this->addForeignKey('#FK_vkuser_has_groups_vkgroups', $this->tableName, 'vk_group_id', 'vkgroups', 'id');
    }

    public function down()
    {
        echo "m170304_115138_vkuser_groups cannot be reverted.\n";

        return false;
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

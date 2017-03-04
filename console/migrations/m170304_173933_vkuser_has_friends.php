<?php

use yii\db\Migration;

class m170304_173933_vkuser_has_friends extends Migration
{
    private $tableName = '{{%vkuser_has_friends}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'vk_user_id' => $this->integer(11)->notNull(),
            'vk_friend_id' => $this->integer(11)->notNull(),
        ]);

        $this->addForeignKey('#FK_vkuser_has_vkprofile', $this->tableName, 'vk_user_id', 'vk_profile', 'id');
        $this->addForeignKey('#FK_vkfriend_has_vkprofile', $this->tableName, 'vk_friend_id', 'vk_profile', 'id');

    }
    public function down()
    {
        echo "m170304_173933_vkuser_has_friends cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170304_114252_user_vk_profile extends Migration
{
    private $tableName = '{{%vk_profile}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'vk_uid' => $this->integer(11)->notNull()->unique(),
            'name' => $this->string(255)->notNull(),
            'surname' => $this->string(255)->notNull(),
        ]);

        $this->addForeignKey('#FK_user_has_vk_profile', $this->tableName, 'user_id', 'user', 'id');
    }

    public function down()
    {
        echo "m170304_114252_user_vk_profile cannot be reverted.\n";

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

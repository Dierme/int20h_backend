<?php

use yii\db\Migration;

class m170304_115138_vkgroups extends Migration
{
    private $tableName = '{{%vkgroups}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'gid'=> $this->integer(11)->notNull()->unique(),
            'group_name' => $this->string()->notNull(),
        ]);

    }

    public function down()
    {
        echo "m170304_115747_vkgroups cannot be reverted.\n";

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

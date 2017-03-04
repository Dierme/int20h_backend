<?php

use yii\db\Migration;

class m170304_120347_tags extends Migration
{
    private $tableName = '{{%tags}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->insert($this->tableName, ['name'=>'sport']);
        $this->insert($this->tableName, ['name'=>'economy']);
        $this->insert($this->tableName, ['name'=>'politics']);
        $this->insert($this->tableName, ['name'=>'media']);
    }

    public function down()
    {
        echo "m170304_120347_tags cannot be reverted.\n";

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

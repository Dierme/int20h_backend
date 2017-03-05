<?php

use yii\db\Migration;

class m170304_202655_category extends Migration
{
    private $tableName = '{{%category}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);


        $this->insert($this->tableName, ['name' => 'politics']);
        $this->insert($this->tableName, ['name' => 'economy']);
        $this->insert($this->tableName, ['name' => 'media']);
        $this->insert($this->tableName, ['name' => 'sports']);
        $this->insert($this->tableName, ['name' => 'wildlife']);
    }

    public function down()
    {
        echo "m170304_220147_category cannot be reverted.\n";

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

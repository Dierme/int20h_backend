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

        $this->insert($this->tableName, ['name'=>'scandal']);
        $this->insert($this->tableName, ['name'=>'government']);

        $this->insert($this->tableName, ['name'=>'money']);
        $this->insert($this->tableName, ['name'=>'bank']);

        $this->insert($this->tableName, ['name'=>'films']);
        $this->insert($this->tableName, ['name'=>'songs']);

        $this->insert($this->tableName, ['name'=>'football']);
        $this->insert($this->tableName, ['name'=>'basketball']);

        $this->insert($this->tableName, ['name'=>'animals']);
        $this->insert($this->tableName, ['name'=>'forest']);
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

<?php

use yii\db\Migration;

class m170304_221830_category_has_tags extends Migration
{
    private $tableName = '{{%category_has_tags}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11)->notNull(),
            'tag_id'    =>  $this->integer(11)->notNull()
        ]);

        $this->addForeignKey('#FK_category_tags_has_category', $this->tableName, 'category_id', 'category', 'id');
        $this->addForeignKey('#FK_category_tags_has_tags', $this->tableName, 'tag_id', 'tags', 'id');

        $this->insert($this->tableName, ['category_id'=>1, 'tag_id'=>1]);
        $this->insert($this->tableName, ['category_id'=>1, 'tag_id'=>2]);

        $this->insert($this->tableName, ['category_id'=>2, 'tag_id'=>3]);
        $this->insert($this->tableName, ['category_id'=>2, 'tag_id'=>4]);

        $this->insert($this->tableName, ['category_id'=>3, 'tag_id'=>5]);
        $this->insert($this->tableName, ['category_id'=>3, 'tag_id'=>6]);

        $this->insert($this->tableName, ['category_id'=>4, 'tag_id'=>7]);
        $this->insert($this->tableName, ['category_id'=>4, 'tag_id'=>8]);

        $this->insert($this->tableName, ['category_id'=>5, 'tag_id'=>9]);
        $this->insert($this->tableName, ['category_id'=>5, 'tag_id'=>10]);

    }

    public function down()
    {
        echo "m170304_221830_category_has_tags cannot be reverted.\n";

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

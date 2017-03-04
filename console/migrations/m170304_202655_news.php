<?php

use yii\db\Migration;

class m170304_202655_news extends Migration
{
    private $tableName = '{{%news}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'header' => $this->string()->notNull(),
            'intro' => $this->string()->notNull(),
            'text' => $this->text(),
            'image' => $this->string(),
            'featured' => $this->smallInteger(1),
            'category_id' => $this->integer(11),
            'created_at' => $this->date(),
            'updated_at' => $this->date()
        ]);

        $this->insert($this->tableName, [
            'header' => 'Sport news',
            'intro' => 'introoooo',
            'text' => 'teeext teeext teeext teeext teeext',
            'image' => 'image1.png',
            'featured'  =>  1,
            'category_id' => 4
        ]);

        $this->insert($this->tableName, [
            'header' => 'Politic news',
            'intro' => 'introoooo',
            'text' => 'teeext teeext teeext teeext teeext',
            'image' => 'image2.png',
            'featured'  =>  1,
            'category_id' => 1
        ]);

        $this->insert($this->tableName, [
            'header' => 'Economy news',
            'intro' => 'introoooo',
            'text' => 'teeext teeext teeext teeext teeext',
            'image' => 'image3.png',
            'featured'  =>  0,
            'category_id' => 2
        ]);

        $this->insert($this->tableName, [
            'header' => 'Media news',
            'intro' => 'introoooo',
            'text' => 'teeext teeext teeext teeext teeext',
            'image' => 'image4.png',
            'featured'  =>  0,
            'category_id' => 3
        ]);


    }

    public function down()
    {
        echo "m170304_202655_news cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170225_173521_languages extends Migration
{
    private $tableName = '{{%language}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'lang_short_name' => $this->string(32)->notNull()->unique(),
            'lang_full_name' => $this->string(32)->notNull()->unique(),
        ]);

        $this->insert($this->tableName, [
            'lang_short_name'=>'en',
            'lang_full_name'=>'English'
        ]);

        $this->insert($this->tableName, [
            'lang_short_name'=>'ru',
            'lang_full_name'=>'Русский'
        ]);
    }

    public function down()
    {
        echo "m170225_173521_languages cannot be reverted.\n";

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

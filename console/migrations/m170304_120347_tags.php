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
            'keywords' => $this->text(),
        ]);

        $this->insert($this->tableName, [
            'name' => 'scandal',
            'keywords' => 'скандал,политика,политический,политич'
        ]);
        $this->insert($this->tableName, [
            'name' => 'government',
            'keywords' => 'государство,государственн,держава'
        ]);

        $this->insert($this->tableName, [
            'name' => 'money',
            'keywords' => 'деньги,казна,бюджет,банк,зарплата'
        ]);
        $this->insert($this->tableName, [
            'name' => 'bank',
            'keywords' => 'банк,деньги,банкрот,кредит'
        ]);
        $this->insert($this->tableName, [
            'name' => 'films',
            'keywords' => 'видео,кино,сериал,фильм'
        ]);
        $this->insert($this->tableName, [
            'name' => 'songs',
            'keywords' => 'песн,песня,петь,музика,аудио,music'
        ]);
        $this->insert($this->tableName, [
            'name' => 'football',
            'keywords' => 'football,мяч,футбол,стадион,голкипер,пенальти,гол'
        ]);
        $this->insert($this->tableName, [
            'name' => 'basketball',
            'keywords' => 'баскетбол'
        ]);
        $this->insert($this->tableName, [
            'name' => 'animals',
            'keywords' => 'кот,собака,щенок,звер,животн'
        ]);
        $this->insert($this->tableName, [
            'name' => 'forest',
            'keywords' => 'лес,листва,тишина,бор,луг,депево,природа,ручей,вода,море,пилит'
        ]);
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

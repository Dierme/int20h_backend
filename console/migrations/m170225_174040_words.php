<?php

use yii\db\Migration;

class m170225_174040_words extends Migration
{
    private $tableName = '{{%words}}';
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'lang_id' => $this->integer(11)->notNull(),
            'text' => $this->string(255)->notNull()->unique(),
            'created_at' => $this->date(),
        ]);

        $this->addForeignKey('#FK_words_has_language', $this->tableName, 'lang_id', 'language', 'id');
    }

    public function down()
    {
        echo "m170225_174040_words cannot be reverted.\n";

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

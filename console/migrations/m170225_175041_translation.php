<?php

use yii\db\Migration;

class m170225_175041_translation extends Migration
{
    private $tableName = '{{%translation}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'word_id' => $this->integer(11)->notNull(),
            'translation_word_id' => $this->integer(11)->notNull(),
        ]);

        $this->addForeignKey('#FK_translation_has_word_id', $this->tableName, 'word_id', 'words', 'id');

        $this->addForeignKey('#FK_translation_word_has_word_id', $this->tableName, 'translation_word_id', 'words', 'id');
    }

    public function down()
    {
        echo "m170225_175041_translation cannot be reverted.\n";

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

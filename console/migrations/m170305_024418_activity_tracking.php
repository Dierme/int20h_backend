<?php

use yii\db\Migration;

class m170305_024418_activity_tracking extends Migration
{
    private $tableName = '{{%activity_tracking}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'news_id'    =>  $this->integer(11)->notNull()
        ]);

        $this->addForeignKey('#FK_activity_has_user', $this->tableName, 'user_id', 'user', 'id');
        $this->addForeignKey('#FK_activity_has_news', $this->tableName, 'news_id', 'news', 'id');
    }

    public function down()
    {
        echo "m170305_024418_activity_tracking cannot be reverted.\n";

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

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `logger`.
 */
class m171002_092651_create_logger_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('logger', [
            'id' => $this->primaryKey(),
            'data' => $this->date()->notNull(),
            'form_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'answer_id' => $this->integer()->notNull(),
            'answer' => $this->string(100)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('logger');
    }
}

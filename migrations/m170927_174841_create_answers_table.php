<?php

use yii\db\Migration;

/**
 * Handles the creation of table `answers`.
 */
class m170927_174841_create_answers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('answers', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'htmlcode' => $this->text()->notNull(),
            'source' => $this->string(20)->defaultValue(null),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('answers');
    }
}

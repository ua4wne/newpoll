<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%own_log}}`.
 */
class m190320_090804_create_own_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%own_log}}', [
            'id' => $this->primaryKey(),
            'own_ecounter_id' => $this->integer()->notNull(),
            'year' => $this->char(4)->notNull(),
            'month' => $this->char(2)->notNull(),
            'encount' => $this->float()->notNull(),
            'delta' => $this->float()->notNull(),
            'price' => $this->float()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%own_log}}');
    }
}

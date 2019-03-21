<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%own_ecounter}}`.
 */
class m190319_104253_create_own_ecounter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%own_ecounter}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'text' => $this->string(100)->notNull(),
            'koeff' => $this->float()->notNull(),
            'tarif' => $this->float()->defaultValue(3.5)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%own_ecounter}}');
    }
}

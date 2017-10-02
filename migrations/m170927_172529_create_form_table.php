<?php

use yii\db\Migration;

/**
 * Handles the creation of table `form`.
 */
class m170927_172529_create_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('form', [
            'id' => $this->primaryKey(),
            'name' => $this->string(80)->notNull(),
            'is_active' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'is_work' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('form');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `form_qty`.
 */
class m181003_070935_create_form_qty_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('form_qty', [
            'id' => $this->primaryKey(),
            'form_id' => $this->integer(11)->unsigned()->notNull(),
            'date' => $this->date()->notNull(),
            'qty' => $this->integer(10)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('form_qty');
    }
}

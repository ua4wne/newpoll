<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tvsource`.
 */
class m170927_181849_create_tvsource_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('tvsource', [
            'id' => $this->primaryKey(),
            'name' => $this->string(80)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tvsource');
    }
}

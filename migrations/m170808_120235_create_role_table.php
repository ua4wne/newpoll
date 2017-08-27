<?php

use yii\db\Migration;

/**
 * Handles the creation of table `role`.
 */
class m170808_120235_create_role_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique(),
            'alias' => $this->string(30)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('role');
    }
}

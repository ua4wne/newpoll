<?php

use yii\db\Migration;

/**
 * Handles the creation of table `place`.
 */
class m170828_155049_create_place_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('place', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'ecounter_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('place');
    }
}

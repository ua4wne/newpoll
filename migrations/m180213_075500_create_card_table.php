<?php

use yii\db\Migration;

/**
 * Handles the creation of table `card`.
 */
class m180213_075500_create_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('card', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'granted' => $this->boolean()->notNull()->defaultValue(0),
            'flags' => $this->smallInteger(),
            'zone' => $this->smallInteger()->notNull(),
            'share' => $this->boolean()->notNull()->defaultValue(0),
            'visitor_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('card');
    }
}

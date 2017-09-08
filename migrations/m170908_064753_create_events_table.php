<?php

use yii\db\Migration;

/**
 * Handles the creation of table `events`.
 */
class m170908_064753_create_events_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('events', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'user_ip' => $this->string(15)->notNull(),
            'type' => $this->string(50)->notNull(),
            'msg' => $this->string(255)->notNull(),
            'is_read' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('events');
    }
}

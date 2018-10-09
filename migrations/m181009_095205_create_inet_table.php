<?php

use yii\db\Migration;

/**
 * Handles the creation of table `inet`.
 */
class m181009_095205_create_inet_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('inet', [
            'id' => $this->primaryKey(),
            'renter_id' => $this->integer()->notNull(),
            'connect' => $this->date(),
            'disconnect' => $this->date(),
            'ip' => $this->string(7)->defaultValue('dynamic'),
            'comment' => $this->string(200),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('inet');
    }
}

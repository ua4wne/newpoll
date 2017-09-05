<?php

use yii\db\Migration;

/**
 * Handles the creation of table `energyLog`.
 */
class m170905_172704_create_energyLog_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('energy_log', [
            'id' => $this->primaryKey(),
            'renter_id' => $this->integer()->notNull(),
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
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('energy_log');
    }
}

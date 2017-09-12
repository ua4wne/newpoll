<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rent_log`.
 */
class m170912_180753_create_rent_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('rent_log', [
            'id' => $this->primaryKey(),
            'renter_id' => $this->integer()->notNull(),
            'data' => $this->date()->notNull(),
            'period1' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period2' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period3' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period4' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period5' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period6' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period7' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period8' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period9' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period10' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'period11' => $this->smallInteger(4)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('rent_log');
    }
}

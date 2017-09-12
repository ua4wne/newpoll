<?php

use yii\db\Migration;

/**
 * Handles the creation of table `visit`.
 */
class m170912_180037_create_visit_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('visit', [
            'id' => $this->primaryKey(),
            'data' => $this->date()->notNull(),
            'hours' => $this->string(2)->notNull(),
            'ucount' => $this->integer(10)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('visit');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `renter`.
 */
class m170824_173041_create_renter_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('renter', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'area' => $this->string(20)->notNull(),
            'agent' => $this->string(50)->notNull(),
            'phone1' => $this->string(20),
            'phone2' => $this->string(20),
            'encounter' => $this->string(20)->unique()->notNull(),
            'koeff' => $this->float()->defaultValue(4.7),
            'place_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'division_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('renter');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `doc_type`.
 */
class m180214_074221_create_doc_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('doc_type', [
            'id' => $this->primaryKey(),
            'text' => $this->string(50)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('doc_type');
    }
}

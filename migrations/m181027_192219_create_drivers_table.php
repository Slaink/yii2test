<?php

use yii\db\Migration;

/**
 * Handles the creation of table `drivers`.
 */
class m181027_192219_create_drivers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('drivers', [
            'id' => $this->primaryKey(),
            'summary' => $this->string(255)->notNull(),
            'photo' => $this->string(255),
            'birthday' => $this->date()->notNull(),
            'is_active' => $this->tinyInteger(2)->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('drivers');
    }
}

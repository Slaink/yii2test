<?php

use yii\db\Migration;

/**
 * Handles the creation of table `buses`.
 */
class m181027_185606_create_buses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('buses', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->unique()->notNull(),
            'avg_speed' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('buses');
    }
}

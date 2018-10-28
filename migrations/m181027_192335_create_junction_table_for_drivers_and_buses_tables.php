<?php

use yii\db\Migration;

/**
 * Handles the creation of table `drivers_buses`.
 * Has foreign keys to the tables:
 *
 * - `drivers`
 * - `buses`
 */
class m181027_192335_create_junction_table_for_drivers_and_buses_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('drivers_buses', [
            'driver_id' => $this->integer(),
            'bus_id' => $this->integer(),
            'PRIMARY KEY(driver_id, bus_id)',
        ]);

        // creates index for column `driver_id`
        $this->createIndex(
            'idx-drivers_buses-driver_id',
            'drivers_buses',
            'driver_id'
        );

        // add foreign key for table `drivers`
        $this->addForeignKey(
            'fk-drivers_buses-driver_id',
            'drivers_buses',
            'driver_id',
            'drivers',
            'id',
            'CASCADE'
        );

        // creates index for column `bus_id`
        $this->createIndex(
            'idx-drivers_buses-bus_id',
            'drivers_buses',
            'bus_id'
        );

        // add foreign key for table `buses`
        $this->addForeignKey(
            'fk-drivers_buses-bus_id',
            'drivers_buses',
            'bus_id',
            'buses',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `drivers`
        $this->dropForeignKey(
            'fk-drivers_buses-driver_id',
            'drivers_buses'
        );

        // drops index for column `driver_id`
        $this->dropIndex(
            'idx-drivers_buses-driver_id',
            'drivers_buses'
        );

        // drops foreign key for table `buses`
        $this->dropForeignKey(
            'fk-drivers_buses-bus_id',
            'drivers_buses'
        );

        // drops index for column `bus_id`
        $this->dropIndex(
            'idx-drivers_buses-bus_id',
            'drivers_buses'
        );

        $this->dropTable('drivers_buses');
    }
}

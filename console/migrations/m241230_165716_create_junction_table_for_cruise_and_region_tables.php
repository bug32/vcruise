<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_region_relation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cruise}}`
 * - `{{%region}}`
 */
class m241230_165716_create_junction_table_for_cruise_and_region_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_region_relation}}', [
            'cruise_id' => $this->integer(),
            'region_id' => $this->integer(),
            'PRIMARY KEY(cruise_id, region_id)',
        ]);

        // creates index for column `cruise_id`
        $this->createIndex(
            '{{%idx-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relation}}',
            'cruise_id'
        );

        // add foreign key for table `{{%cruise}}`
        $this->addForeignKey(
            '{{%fk-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relation}}',
            'cruise_id',
            '{{%cruise}}',
            'id',
            'CASCADE'
        );

        // creates index for column `region_id`
        $this->createIndex(
            '{{%idx-cruise_region_relation-region_id}}',
            '{{%cruise_region_relation}}',
            'region_id'
        );

        // add foreign key for table `{{%region}}`
        $this->addForeignKey(
            '{{%fk-cruise_region_relation-region_id}}',
            '{{%cruise_region_relation}}',
            'region_id',
            '{{%region}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cruise}}`
        $this->dropForeignKey(
            '{{%fk-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relation}}'
        );

        // drops index for column `cruise_id`
        $this->dropIndex(
            '{{%idx-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relation}}'
        );

        // drops foreign key for table `{{%region}}`
        $this->dropForeignKey(
            '{{%fk-cruise_region_relation-region_id}}',
            '{{%cruise_region_relation}}'
        );

        // drops index for column `region_id`
        $this->dropIndex(
            '{{%idx-cruise_region_relation-region_id}}',
            '{{%cruise_region_relation}}'
        );

        $this->dropTable('{{%cruise_region_relation}}');
    }
}

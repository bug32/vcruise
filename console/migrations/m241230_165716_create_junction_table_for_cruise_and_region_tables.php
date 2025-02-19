<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_region_relations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cruise}}`
 * - `{{%regions}}`
 */
class m241230_165716_create_junction_table_for_cruise_and_region_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_region_relations}}', [
            'cruise_id' => $this->integer(),
            'region_id' => $this->integer(),
            'PRIMARY KEY(cruise_id, region_id)',
        ]);

        // creates index for column `cruise_id`
        $this->createIndex(
            '{{%idx-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relations}}',
            'cruise_id'
        );

        // add foreign key for table `{{%cruise}}`
        $this->addForeignKey(
            '{{%fk-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relations}}',
            'cruise_id',
            '{{%cruises}}',
            'id',
            'CASCADE'
        );

        // creates index for column `region_id`
        $this->createIndex(
            '{{%idx-cruise_region_relation-region_id}}',
            '{{%cruise_region_relations}}',
            'region_id'
        );

        // add foreign key for table `{{%regions}}`
        $this->addForeignKey(
            '{{%fk-cruise_region_relation-region_id}}',
            '{{%cruise_region_relations}}',
            'region_id',
            '{{%regions}}',
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
            '{{%cruise_region_relations}}'
        );

        // drops index for column `cruise_id`
        $this->dropIndex(
            '{{%idx-cruise_region_relation-cruise_id}}',
            '{{%cruise_region_relations}}'
        );

        // drops foreign key for table `{{%regions}}`
        $this->dropForeignKey(
            '{{%fk-cruise_region_relation-region_id}}',
            '{{%cruise_region_relations}}'
        );

        // drops index for column `region_id`
        $this->dropIndex(
            '{{%idx-cruise_region_relation-region_id}}',
            '{{%cruise_region_relations}}'
        );

        $this->dropTable('{{%cruise_region_relations}}');
    }
}

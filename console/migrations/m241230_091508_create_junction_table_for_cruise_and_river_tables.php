<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_river_relations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cruise}}`
 * - `{{%rivers}}`
 */
class m241230_091508_create_junction_table_for_cruise_and_river_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_river_relations}}', [
            'cruise_id' => $this->integer(),
            'river_id' => $this->integer(),
            'PRIMARY KEY(cruise_id, river_id)',
        ]);

        // creates index for column `cruise_id`
        $this->createIndex(
            '{{%idx-cruise_river_relation-cruise_id}}',
            '{{%cruise_river_relations}}',
            'cruise_id'
        );

        // add foreign key for table `{{%cruise}}`
        $this->addForeignKey(
            '{{%fk-cruise_river_relation-cruise_id}}',
            '{{%cruise_river_relations}}',
            'cruise_id',
            '{{%cruises}}',
            'id',
            'CASCADE'
        );

        // creates index for column `river_id`
        $this->createIndex(
            '{{%idx-cruise_river_relation-river_id}}',
            '{{%cruise_river_relations}}',
            'river_id'
        );

        // add foreign key for table `{{%rivers}}`
        $this->addForeignKey(
            '{{%fk-cruise_river_relation-river_id}}',
            '{{%cruise_river_relations}}',
            'river_id',
            '{{%rivers}}',
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
            '{{%fk-cruise_river_relation-cruise_id}}',
            '{{%cruise_river_relations}}'
        );

        // drops index for column `cruise_id`
        $this->dropIndex(
            '{{%idx-cruise_river_relation-cruise_id}}',
            '{{%cruise_river_relations}}'
        );

        // drops foreign key for table `{{%rivers}}`
        $this->dropForeignKey(
            '{{%fk-cruise_river_relation-river_id}}',
            '{{%cruise_river_relations}}'
        );

        // drops index for column `river_id`
        $this->dropIndex(
            '{{%idx-cruise_river_relation-river_id}}',
            '{{%cruise_river_relations}}'
        );

        $this->dropTable('{{%cruise_river_relations}}');
    }
}

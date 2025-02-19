<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_suggestion_relations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cruise}}`
 * - `{{%suggestions}}`
 */
class m241230_165512_create_junction_table_for_cruise_and_suggestion_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_suggestion_relations}}', [
            'cruise_id' => $this->integer(),
            'suggestion_id' => $this->integer(),
            'PRIMARY KEY(cruise_id, suggestion_id)',
        ]);

        // creates index for column `cruise_id`
        $this->createIndex(
            '{{%idx-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relations}}',
            'cruise_id'
        );

        // add foreign key for table `{{%cruise}}`
        $this->addForeignKey(
            '{{%fk-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relations}}',
            'cruise_id',
            '{{%cruises}}',
            'id',
            'CASCADE'
        );

        // creates index for column `suggestion_id`
        $this->createIndex(
            '{{%idx-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relations}}',
            'suggestion_id'
        );

        // add foreign key for table `{{%suggestions}}`
        $this->addForeignKey(
            '{{%fk-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relations}}',
            'suggestion_id',
            '{{%suggestions}}',
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
            '{{%fk-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relations}}'
        );

        // drops index for column `cruise_id`
        $this->dropIndex(
            '{{%idx-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relations}}'
        );

        // drops foreign key for table `{{%suggestions}}`
        $this->dropForeignKey(
            '{{%fk-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relations}}'
        );

        // drops index for column `suggestion_id`
        $this->dropIndex(
            '{{%idx-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relations}}'
        );

        $this->dropTable('{{%cruise_suggestion_relations}}');
    }
}

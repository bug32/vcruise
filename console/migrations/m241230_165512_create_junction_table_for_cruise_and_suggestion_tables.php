<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_suggestion_relation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cruise}}`
 * - `{{%suggestion}}`
 */
class m241230_165512_create_junction_table_for_cruise_and_suggestion_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_suggestion_relation}}', [
            'cruise_id' => $this->integer(),
            'suggestion_id' => $this->integer(),
            'PRIMARY KEY(cruise_id, suggestion_id)',
        ]);

        // creates index for column `cruise_id`
        $this->createIndex(
            '{{%idx-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relation}}',
            'cruise_id'
        );

        // add foreign key for table `{{%cruise}}`
        $this->addForeignKey(
            '{{%fk-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relation}}',
            'cruise_id',
            '{{%cruise}}',
            'id',
            'CASCADE'
        );

        // creates index for column `suggestion_id`
        $this->createIndex(
            '{{%idx-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relation}}',
            'suggestion_id'
        );

        // add foreign key for table `{{%suggestion}}`
        $this->addForeignKey(
            '{{%fk-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relation}}',
            'suggestion_id',
            '{{%suggestion}}',
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
            '{{%cruise_suggestion_relation}}'
        );

        // drops index for column `cruise_id`
        $this->dropIndex(
            '{{%idx-cruise_suggestion_relation-cruise_id}}',
            '{{%cruise_suggestion_relation}}'
        );

        // drops foreign key for table `{{%suggestion}}`
        $this->dropForeignKey(
            '{{%fk-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relation}}'
        );

        // drops index for column `suggestion_id`
        $this->dropIndex(
            '{{%idx-cruise_suggestion_relation-suggestion_id}}',
            '{{%cruise_suggestion_relation}}'
        );

        $this->dropTable('{{%cruise_suggestion_relation}}');
    }
}

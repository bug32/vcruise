<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%suggestion_ship_relations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%suggestions}}`
 * - `{{%ships}}`
 */
class m250103_155032_create_junction_table_for_suggestion_and_ship_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%suggestion_ship_relations}}', [
            'suggestion_id' => $this->integer(),
            'ship_id' => $this->integer(),
            'priority' => $this->integer()->notNull()->defaultValue(0),
            'PRIMARY KEY(suggestion_id, ship_id)',
        ]);

        // creates index for column `suggestion_id`
        $this->createIndex(
            '{{%idx-suggestion_ship_relation-suggestion_id}}',
            '{{%suggestion_ship_relations}}',
            'suggestion_id'
        );

        // add foreign key for table `{{%suggestions}}`
        $this->addForeignKey(
            '{{%fk-suggestion_ship_relation-suggestion_id}}',
            '{{%suggestion_ship_relations}}',
            'suggestion_id',
            '{{%suggestions}}',
            'id',
            'CASCADE'
        );

        // creates index for column `ship_id`
        $this->createIndex(
            '{{%idx-suggestion_ship_relation-ship_id}}',
            '{{%suggestion_ship_relations}}',
            'ship_id'
        );

        // add foreign key for table `{{%ships}}`
        $this->addForeignKey(
            '{{%fk-suggestion_ship_relation-ship_id}}',
            '{{%suggestion_ship_relations}}',
            'ship_id',
            '{{%ships}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%suggestions}}`
        $this->dropForeignKey(
            '{{%fk-suggestion_ship_relation-suggestion_id}}',
            '{{%suggestion_ship_relations}}'
        );

        // drops index for column `suggestion_id`
        $this->dropIndex(
            '{{%idx-suggestion_ship_relation-suggestion_id}}',
            '{{%suggestion_ship_relations}}'
        );

        // drops foreign key for table `{{%ships}}`
        $this->dropForeignKey(
            '{{%fk-suggestion_ship_relation-ship_id}}',
            '{{%suggestion_ship_relations}}'
        );

        // drops index for column `ship_id`
        $this->dropIndex(
            '{{%idx-suggestion_ship_relation-ship_id}}',
            '{{%suggestion_ship_relations}}'
        );

        $this->dropTable('{{%suggestion_ship_relations}}');
    }
}

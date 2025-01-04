<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabin_type_service_relation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cabin_type}}`
 * - `{{%service}}`
 */
class m250103_192414_create_junction_table_for_cabin_type_and_service_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabin_type_service_relation}}', [
            'cabin_type_id' => $this->integer(),
            'service_id' => $this->integer(),
            'PRIMARY KEY(cabin_type_id, service_id)',
        ]);

        // creates index for column `cabin_type_id`
        $this->createIndex(
            '{{%idx-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relation}}',
            'cabin_type_id'
        );

        // add foreign key for table `{{%cabin_type}}`
        $this->addForeignKey(
            '{{%fk-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relation}}',
            'cabin_type_id',
            '{{%cabin_type}}',
            'id',
            'CASCADE'
        );

        // creates index for column `service_id`
        $this->createIndex(
            '{{%idx-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relation}}',
            'service_id'
        );

        // add foreign key for table `{{%service}}`
        $this->addForeignKey(
            '{{%fk-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relation}}',
            'service_id',
            '{{%service}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cabin_type}}`
        $this->dropForeignKey(
            '{{%fk-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relation}}'
        );

        // drops index for column `cabin_type_id`
        $this->dropIndex(
            '{{%idx-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relation}}'
        );

        // drops foreign key for table `{{%service}}`
        $this->dropForeignKey(
            '{{%fk-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relation}}'
        );

        // drops index for column `service_id`
        $this->dropIndex(
            '{{%idx-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relation}}'
        );

        $this->dropTable('{{%cabin_type_service_relation}}');
    }
}

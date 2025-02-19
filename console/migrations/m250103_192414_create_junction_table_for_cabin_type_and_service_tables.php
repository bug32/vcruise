<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabin_type_service_relations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cabin_types}}`
 * - `{{%services}}`
 */
class m250103_192414_create_junction_table_for_cabin_type_and_service_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabin_type_service_relations}}', [
            'cabin_type_id' => $this->integer(),
            'service_id' => $this->integer(),
            'PRIMARY KEY(cabin_type_id, service_id)',
        ]);

        // creates index for column `cabin_type_id`
        $this->createIndex(
            '{{%idx-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relations}}',
            'cabin_type_id'
        );

        // add foreign key for table `{{%cabin_types}}`
        $this->addForeignKey(
            '{{%fk-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relations}}',
            'cabin_type_id',
            '{{%cabin_types}}',
            'id',
            'CASCADE'
        );

        // creates index for column `service_id`
        $this->createIndex(
            '{{%idx-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relations}}',
            'service_id'
        );

        // add foreign key for table `{{%services}}`
        $this->addForeignKey(
            '{{%fk-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relations}}',
            'service_id',
            '{{%services}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cabin_types}}`
        $this->dropForeignKey(
            '{{%fk-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relations}}'
        );

        // drops index for column `cabin_type_id`
        $this->dropIndex(
            '{{%idx-cabin_type_service_relation-cabin_type_id}}',
            '{{%cabin_type_service_relations}}'
        );

        // drops foreign key for table `{{%services}}`
        $this->dropForeignKey(
            '{{%fk-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relations}}'
        );

        // drops index for column `service_id`
        $this->dropIndex(
            '{{%idx-cabin_type_service_relation-service_id}}',
            '{{%cabin_type_service_relations}}'
        );

        $this->dropTable('{{%cabin_type_service_relations}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_popular_route_relation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cruise}}`
 * - `{{%popular_route}}`
 */
class m241230_164434_create_junction_table_for_cruise_and_popular_route_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_popular_route_relation}}', [
            'cruise_id' => $this->integer(),
            'popular_route_id' => $this->integer(),
            'PRIMARY KEY(cruise_id, popular_route_id)',
        ]);

        // creates index for column `cruise_id`
        $this->createIndex(
            '{{%idx-cruise_popular_route_relation-cruise_id}}',
            '{{%cruise_popular_route_relation}}',
            'cruise_id'
        );

        // add foreign key for table `{{%cruise}}`
        $this->addForeignKey(
            '{{%fk-cruise_popular_route_relation-cruise_id}}',
            '{{%cruise_popular_route_relation}}',
            'cruise_id',
            '{{%cruise}}',
            'id',
            'CASCADE'
        );

        // creates index for column `popular_route_id`
        $this->createIndex(
            '{{%idx-cruise_popular_route_relation-popular_route_id}}',
            '{{%cruise_popular_route_relation}}',
            'popular_route_id'
        );

        // add foreign key for table `{{%popular_route}}`
        $this->addForeignKey(
            '{{%fk-cruise_popular_route_relation-popular_route_id}}',
            '{{%cruise_popular_route_relation}}',
            'popular_route_id',
            '{{%popular_route}}',
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
            '{{%fk-cruise_popular_route_relation-cruise_id}}',
            '{{%cruise_popular_route_relation}}'
        );

        // drops index for column `cruise_id`
        $this->dropIndex(
            '{{%idx-cruise_popular_route_relation-cruise_id}}',
            '{{%cruise_popular_route_relation}}'
        );

        // drops foreign key for table `{{%popular_route}}`
        $this->dropForeignKey(
            '{{%fk-cruise_popular_route_relation-popular_route_id}}',
            '{{%cruise_popular_route_relation}}'
        );

        // drops index for column `popular_route_id`
        $this->dropIndex(
            '{{%idx-cruise_popular_route_relation-popular_route_id}}',
            '{{%cruise_popular_route_relation}}'
        );

        $this->dropTable('{{%cruise_popular_route_relation}}');
    }
}

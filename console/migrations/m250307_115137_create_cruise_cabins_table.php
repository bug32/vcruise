<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_cabins}}`.
 */
class m250307_115137_create_cruise_cabins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_cabins}}', [
            'id'         => $this->primaryKey(),
            'cruise_id'  => $this->integer()->notNull(),
            'cabin_json' => $this->json()->notNull(),

            'created_at' => $this->integer()->defaultExpression('NOW()'),
        ]);

        $this->createIndex('idx_cruise_cabins_cruise_id', '{{%cruise_cabins}}', 'cruise_id');

        $this->addForeignKey('fk_cruise_cabins_cruise_id',
            '{{%cruise_cabins}}',
            'cruise_id',
            '{{%cruises}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_cruise_cabins_cruise_id', '{{%cruise_cabins}}');

        $this->dropTable('{{%cruise_cabins}}');
    }
}

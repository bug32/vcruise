<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider_ship}}`.
 */
class m250103_074632_create_provider_ship_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider_ship}}', [
            'id'            => $this->primaryKey(),
            'provider_name' => $this->string()->notNull(),
            'foreign_id'    => $this->string()->notNull(),
            'internal_id'   => $this->integer()->notNull(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%provider_ship}}', 'Производители кораблей');

        $this->createIndex(
            'idx_provider_ship_provider_name-foreign_id',
            '{{%provider_ship}}',
            ['provider_name', 'foreign_id']
        );

        $this->addForeignKey(
            'fk_provider_ship_internal',
            '{{%provider_ship}}',
            'internal_id',
            '{{%ship}}',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_provider_ship_internal', '{{%provider_ship}}');
        $this->dropTable('{{%provider_ship}}');
    }
}

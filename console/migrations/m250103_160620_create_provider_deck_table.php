<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider_deck}}`.
 */
class m250103_160620_create_provider_deck_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider_deck}}', [
            'id' => $this->primaryKey(),
            'provider_name' => $this->string()->notNull(),
            'foreign_id' => $this->string()->notNull(),
            'internal_id' => $this->integer()->notNull(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%provider_deck}}', 'Соотношение внутрениих и внешних ID для парсинга палуб');

        $this->createIndex('idx_provider_deck_provider_name-foreign_id', '{{%provider_deck}}', [
            'provider_name', 'foreign_id'], TRUE);

        $this->createIndex('idx_provider_deck_provider_name-internal', '{{%provider_deck}}', [
            'provider_name', 'internal_id'], TRUE);

        $this->addForeignKey('fk_provider_deck_internal', '{{%provider_deck}}', 'internal_id', '{{%deck}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_provider_deck_internal', '{{%provider_deck}}');
        $this->dropTable('{{%provider_deck}}');
    }
}

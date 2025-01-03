<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider_cabin}}`.
 */
class m250103_162404_create_provider_cabin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider_cabin}}', [
            'id' => $this->primaryKey(),
            'provider_name' => $this->string()->notNull(),
            'foreign_id' => $this->string()->notNull(),
            'internal_id' => $this->integer()->notNull(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%provider_cabin}}', 'Соотношение внутрениих и внешних ID для парсинга кают');

        $this->createIndex('provider_cabin_provider_name_foreign_id', '{{%provider_cabin}}', ['provider_name', 'foreign_id'], true);
        $this->createIndex('provider_cabin_provider_name_internal_id', '{{%provider_cabin}}', ['provider_name', 'internal_id'], true);

        $this->addForeignKey('fk_provider_cabin_internal', '{{%provider_cabin}}', 'internal_id', '{{%cabin}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_provider_cabin_internal', '{{%provider_cabin}}');
        $this->dropTable('{{%provider_cabin}}');
    }
}

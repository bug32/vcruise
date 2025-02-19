<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decks}}`.
 */
class m241229_101637_create_deck_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decks}}', [
            'id' => $this->primaryKey(),
            'ship_id' => $this->integer()->notNull(),

            'priority' => $this->integer()->notNull()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),

            'status' => $this->smallInteger()->defaultValue(10),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%decks}}', 'Палубы');

        $this->addForeignKey('fk_deck_ship', '{{%decks}}', 'ship_id', '{{%ships}}', 'id', 'CASCADE');
        $this->createIndex('idx_deck_ship', '{{%decks}}', 'ship_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_deck_ship', '{{%decks}}');

        $this->dropTable('{{%decks}}');
    }
}

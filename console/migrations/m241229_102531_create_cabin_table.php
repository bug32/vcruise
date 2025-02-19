<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabins}}`.
 */
class m241229_102531_create_cabin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabins}}', [
            'id' => $this->primaryKey(),
            'ship_id' => $this->integer()->notNull(),
            'deck_id' => $this->integer()->notNull(),
            'cabin_type_id' => $this->integer()->notNull(),

            'name' => $this->string()->notNull(),
            'description' => $this->text(),

            'places' => $this->integer()->notNull()->defaultValue(1),
            'additionalPlaces' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%cabins}}', 'Каюты');

        $this->addForeignKey('fk_cabin_ship', '{{%cabins}}', 'ship_id', '{{%ships}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cabin_deck', '{{%cabins}}', 'deck_id', '{{%decks}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cabin_cabin_type', '{{%cabins}}', 'cabin_type_id', '{{%cabin_types}}', 'id', 'CASCADE');


        $this->createIndex('idx_cabin_ship', '{{%cabins}}', 'ship_id');
        $this->createIndex('idx_cabin_deck', '{{%cabins}}', 'deck_id');
        $this->createIndex('idx_cabin_cabin_type', '{{%cabins}}', 'cabin_type_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_cabin_ship', '{{%cabins}}');
        $this->dropForeignKey('fk_cabin_deck', '{{%cabins}}');
        $this->dropForeignKey('fk_cabin_cabin_type', '{{%cabins}}');

        $this->dropTable('{{%cabins}}');
    }
}

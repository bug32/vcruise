<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabin}}`.
 */
class m241229_102531_create_cabin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabin}}', [
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

        $this->addCommentOnTable('{{%cabin}}', 'Каюты');

        $this->addForeignKey('fk_cabin_ship', '{{%cabin}}', 'ship_id', '{{%ship}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cabin_deck', '{{%cabin}}', 'deck_id', '{{%deck}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cabin_cabin_type', '{{%cabin}}', 'cabin_type_id', '{{%cabin_type}}', 'id', 'CASCADE');


        $this->createIndex('idx_cabin_ship', '{{%cabin}}', 'ship_id');
        $this->createIndex('idx_cabin_deck', '{{%cabin}}', 'deck_id');
        $this->createIndex('idx_cabin_cabin_type', '{{%cabin}}', 'cabin_type_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_cabin_ship', '{{%cabin}}');
        $this->dropForeignKey('fk_cabin_deck', '{{%cabin}}');
        $this->dropForeignKey('fk_cabin_cabin_type', '{{%cabin}}');

        $this->dropTable('{{%cabin}}');
    }
}

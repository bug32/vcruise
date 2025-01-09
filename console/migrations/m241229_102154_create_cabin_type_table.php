<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabin_type}}`.
 */
class m241229_102154_create_cabin_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabin_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'ship_id' => $this->integer()->notNull(),

            'priority' => $this->integer()->notNull()->defaultValue(0),
            'isEco' => $this->boolean()->notNull()->defaultValue(false),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%cabin_type}}', 'Типы кают');

        $this->addForeignKey('fk_cabin_type_ship', '{{%cabin_type}}', 'ship_id', '{{%ship}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cabin_type}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%public_places}}`.
 */
class m241229_122236_create_public_place_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%public_places}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'photo' => $this->string(),
            'icon' => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%public_places}}', 'Публичные места на теплоходе');

        $this->createIndex('idx_public_place_slug', '{{%public_places}}', 'slug');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%public_places}}');
    }
}

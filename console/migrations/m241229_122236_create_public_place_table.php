<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%public_place}}`.
 */
class m241229_122236_create_public_place_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%public_place}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'photo' => $this->string(),
            'icon' => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%public_place}}', 'Публичные места на теплоходе');

        $this->createIndex('idx_public_place_slug', '{{%public_place}}', 'slug');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%public_place}}');
    }
}

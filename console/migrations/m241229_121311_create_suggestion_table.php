<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%suggestions}}`.
 */
class m241229_121311_create_suggestion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%suggestions}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'label' => $this->string()->comment('Название для отображения в интерфейсе'),
            'description' => $this->text(),
            'icon' => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%suggestions}}', 'Дополнительно на борту');

        $this->createIndex('idx_suggestion_slug', '{{%suggestions}}', 'slug');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%suggestions}}');
    }
}

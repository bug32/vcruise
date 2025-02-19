<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%services}}`.
 */
class m241229_121052_create_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%services}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'icon' => $this->string(),
            'description' => $this->text(),
            'priority' => $this->integer()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%services}}', 'Сервисы на борту');

        $this->createIndex('idx_service_slug', '{{%services}}', 'slug');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%services}}');
    }
}

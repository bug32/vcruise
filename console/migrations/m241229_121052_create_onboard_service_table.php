<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%onboard_service}}`.
 */
class m241229_121052_create_onboard_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%onboard_service}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'icon' => $this->string(),
            'description' => $this->text(),
            'priority' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%onboard_service}}', 'Сервисы на борту');

        $this->createIndex('idx_onboard_service_slug', '{{%onboard_service}}', 'slug');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%onboard_service}}');
    }
}

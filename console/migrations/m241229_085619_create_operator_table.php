<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%operators}}`.
 */
class m241229_085619_create_operator_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%operators}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'slug'        => $this->string()->notNull()->unique(),
            'status'      => $this->smallInteger()->defaultValue(10),
            'rating'      => $this->smallInteger()->defaultValue(0),
            'description' => $this->text(),
            'logo'        => $this->string(),
            'url'         => $this->string(),
            'phone'       => $this->string(),
            'email'       => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%operators}}', 'Операторы');

        $this->createIndex(
            'idx-operator-status',
            '{{%operators}}',
            'status'
        );
        $this->createIndex('idx_operator_slug-status', '{{%operators}}', ['slug', 'status'], TRUE);

        $this->insert('{{%operators}}', [
            'id' => 1,
            'name' => 'Не указан',
            'slug' => 'not-specified',
            'status' => 10
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%operators}}');
    }
}

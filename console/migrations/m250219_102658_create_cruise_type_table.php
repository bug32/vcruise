<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_type}}`.
 */
class m250219_102658_create_cruise_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->insert('{{%cruise_type}}', [
            'id' => 1,
            'name' => 'Не указан',
            'slug' => 'unknown',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cruise_type}}');
    }
}

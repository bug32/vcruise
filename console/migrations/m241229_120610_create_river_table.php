<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rivers}}`.
 */
class m241229_120610_create_river_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%river}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'description' => $this->text(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->createIndex('idx_river_slug', '{{%river}}', 'slug');

        $this->insert('{{%river}}', [
            'id' => 0,
            'name' => 'Не указано',
            'slug' => 'not-specified',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%river}}');
    }
}

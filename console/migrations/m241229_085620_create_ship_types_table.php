<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ship_types}}`.
 */
class m241229_085620_create_ship_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ship_types}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'slug'        => $this->string()->notNull()->unique(),
            'status'      => $this->smallInteger()->defaultValue(10),
            'priority' => $this->integer()->defaultValue(0),
            'icon'        => $this->string(),
            'description' => $this->text(),
            'created_at'  => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at'  => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%ship_types}}', 'Типы кораблей');

        $this->createIndex('idx_type_ship_slug-status', '{{%ship_types}}', ['slug', 'status'], TRUE);
        $this->createIndex('idx_type_ship_status', '{{%ship_types}}', 'status');

        $this->insert('{{%ship_types}}', [
            'id' => 1,
            'name' => 'Не указано',
            'slug' => 'not-specified',
            'status' => 10,
            'priority' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ship_types}}');
    }
}

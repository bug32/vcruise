<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%type_ship}}`.
 */
class m241229_085620_create_type_ship_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%type_ship}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'slug'        => $this->string()->notNull()->unique(),
            'status'      => $this->smallInteger()->notNull()->defaultValue(10),
            'priority' => $this->integer()->notNull()->defaultValue(0),
            'icon'        => $this->string(),
            'description' => $this->text(),
            'created_at'  => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at'  => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%type_ship}}', 'Типы кораблей');

        $this->createIndex('idx_type_ship_slug-status', '{{%type_ship}}', ['slug', 'status'], TRUE);
        $this->createIndex('idx_type_ship_status', '{{%type_ship}}', 'status');

        $this->insert('{{%type_ship}}', [
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
        $this->dropTable('{{%type_ship}}');
    }
}

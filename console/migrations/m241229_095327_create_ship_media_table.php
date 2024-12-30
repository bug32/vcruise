<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ship_media}}`.
 */
class m241229_095327_create_ship_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*
         * key - для объединения картинок в группы по ключу ( напимер фото капитана, фото бара и тд )         * */
        $this->createTable('{{%ship_media}}', [
            'id' => $this->primaryKey(),
            'ship_id' => $this->integer(),

            'name' => $this->string()->notNull(),
            'key' => $this->string()->comment('Для объединения картинок в группы по ключу'),

            'mime_type' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'size' => $this->integer()->notNull(),
            'priority' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%ship_media}}', 'Медиа связанное с кораблем, каютами и т.д.');

        $this->addForeignKey('fk_ship_media_ship_id', '{{%ship_media}}', 'ship_id', '{{%ship}}', 'id', 'CASCADE');
        $this->createIndex('idx_ship_media_ship_id-key', '{{%ship_media}}', ['ship_id', 'key']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_ship_media_ship_id', '{{%ship_media}}');

        $this->dropTable('{{%ship_media}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city_media}}`.
 */
class m250106_084400_create_city_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city_media}}', [
            'id' => $this->primaryKey(),

            'city_id' => $this->integer()->notNull(),
            'alt'     => $this->string(),

            'mime_type' => $this->string(),
            'url'       => $this->string()->notNull(),
            'size'      => $this->integer()->defaultValue(0),
            'priority'  => $this->integer()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%city_media}}', 'Фото и видео достопримечательностей города');

        $this->createIndex('idx_city_media_city_id', '{{%city_media}}', 'city_id');

        $this->addForeignKey(
            'fk_city_media_city',
            '{{%city_media}}',
            'city_id',
            '{{%city}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%city_media}}');
    }
}

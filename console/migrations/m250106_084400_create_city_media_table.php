<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city_medias}}`.
 */
class m250106_084400_create_city_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city_medias}}', [
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

        $this->addCommentOnTable('{{%city_medias}}', 'Фото и видео достопримечательностей города');

        $this->createIndex('idx_city_media_city_id', '{{%city_medias}}', 'city_id');

        $this->addForeignKey(
            'fk_city_media_city',
            '{{%city_medias}}',
            'city_id',
            '{{%cities}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%city_medias}}');
    }
}

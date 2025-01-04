<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m241229_115151_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'slug'        => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'logo'        => $this->string(),
            'country_id'  => $this->integer()->notNull(),

            /* Координаты города на карте */
            'long'        => $this->string(),
            'lat'         => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%city}}', 'Города');

        $this->addForeignKey('fk_city_country', '{{%city}}', 'country_id', '{{%country}}', 'id', 'CASCADE');

        $this->createIndex('idx_city_slug', '{{%city}}', 'slug');

        $this->insert('{{%city}}', [
            'name' => 'Не указан',
            'slug' => 'not-specified',
            'country_id' => 0
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%city}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%port}}`.
 */
class m241230_080015_create_port_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%port}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'city_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'address' => $this->string(),

            'coordinates' => $this->string()->comment('Координаты порта'),
            'map'=> $this->string()->comment('ссылка на карту яндекс'),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%port}}');
    }
}

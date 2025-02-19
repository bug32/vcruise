<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ships}}`.
 */
class m241229_085621_create_ship_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%ships}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),

            'typeId' => $this->integer()->defaultValue(0)->comment('Типкорабля'),
            'operatorId' => $this->integer()->defaultValue(0)->comment('Судовладелец'),

            'stars' => $this->smallInteger(),
            'captain' => $this->string(),
            'cruiseDirector' => $this->string(),
            'cruiseDirectorTel' => $this->string(),
            'restaurantDirector' => $this->string(),

            'description' => $this->text(),
            'descriptionBig' => $this->text(),
            'discounts' => $this->text(),

            'status' => $this->smallInteger()->defaultValue(10),
            'priority' => $this->integer()->defaultValue(0),

            'length' => $this->string(),
            'width' => $this->string(),
            'passengers' => $this->integer(),
            'decksTotal' => $this->smallInteger()->comment('Количество палуб'),
            'cabinsTotal' => $this->smallInteger()->comment('Количество кают'),

            'additional' => $this->text()->comment('Дополнительно на борту'),
            'include' => $this->text()->comment('Включено на борту'),
            'currency' => $this->string()->comment('Валюта на борту'),
            'video' => $this->string()->comment('Видео о корабле'),
            '3dtour' => $this->string()->comment('3D тур по караблю'),

            'scheme' => $this->string()->comment('Схема'),

            'year' => $this->integer()->comment('Год выпуска'),
            'yearRenovation' => $this->integer()->comment('Год ремонта'),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%ships}}', 'Корабли');

        $this->createIndex('idx-ship-slug_status', '{{%ships}}', ['slug', 'status'], true);
        $this->createIndex('idx-ship-typeId', '{{%ships}}', 'typeId');
        $this->createIndex('idx-ship-operatorId', '{{%ships}}', 'operatorId');

        $this->addForeignKey('fk-ship-typeId', '{{%ships}}', 'typeId', '{{%ship_types}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-ship-operatorId', '{{%ships}}', 'operatorId', '{{%operators}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {

        $this->dropForeignKey('fk-ship-typeId', '{{%ships}}');
        $this->dropForeignKey('fk-ship-operatorId', '{{%ships}}');

        $this->dropTable('{{%ships}}');
    }
}

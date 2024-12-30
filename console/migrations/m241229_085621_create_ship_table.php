<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ship}}`.
 */
class m241229_085621_create_ship_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%ship}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),

            'typeId' => $this->integer()->notNull()->comment('Типкорабля'),
            'operatorId' => $this->integer()->notNull()->comment('Судовладелец'),

            'stars' => $this->smallInteger(),
            'captain' => $this->string(),
            'criuseDirector' => $this->string(),
            'cruiseDirectorTel' => $this->string(),
            'restaurantDirector' => $this->string(),

            'description' => $this->text(),
            'descriptionBig' => $this->text(),
            'discounts' => $this->text(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'priority' => $this->integer()->notNull()->defaultValue(0),

            'length' => $this->string(),
            'width' => $this->string(),
            'passengers' => $this->integer(),
            'decks' => $this->smallInteger(),

            'additional' => $this->text()->comment('Дополнительно на борту'),
            'currency' => $this->string()->comment('Валюта на борту'),
            'video' => $this->string()->comment('Видео о корабле'),
            '3dtour' => $this->string()->comment('3D тур по караблю'),

            'scheme' => $this->string()->comment('Схема'),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%ship}}', 'Корабли');

        $this->createIndex('idx-ship-slug_status', '{{%ship}}', ['slug', 'status'], true);
        $this->createIndex('idx-ship-typeId', '{{%ship}}', 'typeId');
        $this->createIndex('idx-ship-operatorId', '{{%ship}}', 'operatorId');

        $this->addForeignKey('fk-ship-typeId', '{{%ship}}', 'typeId', '{{%type_ship}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-ship-operatorId', '{{%ship}}', 'operatorId', '{{%operator}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {

        $this->dropForeignKey('fk-ship-typeId', '{{%ship}}');
        $this->dropForeignKey('fk-ship-operatorId', '{{%ship}}');

        $this->dropTable('{{%ship}}');
    }
}

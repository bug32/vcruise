<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruises}}`.
 */
class m241230_080833_create_cruise_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruises}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'route' => $this->text()->notNull(),
            'route_short' => $this->text(),
            'description' => $this->text(),
            'include' => $this->text()->comment('Включено'),
            'additional' => $this->text()->comment('Дополнительно'),
            'discounts' => $this->text()->comment('Скидки'),
            'map' => $this->string()->comment('ссылка на карту маршрута'),
            'status' => $this->smallInteger()->defaultValue(10),

            'date_start' => $this->date()->notNull(),
            'date_end' => $this->date()->notNull(),
            'date_start_timestamp' => $this->integer()->notNull(),
            'date_end_timestamp' => $this->integer()->notNull(),
            'days' => $this->smallInteger(),
            'nights' => $this->smallInteger(),

            'min_price' => $this->integer(),
            'max_price' => $this->integer(),
            'currency' => $this->string()->defaultValue(1)->comment('Валюта'),

            'free_cabins' => $this->integer()->defaultValue(0),

            'ship_id' => $this->integer()->notNull(),

            'port_start_id' => $this->integer()->defaultValue(1), // 'port_id'
            'port_end_id' => $this->integer()->defaultValue(1), // 'port_id'
            'dock_start' => $this->string(),

            'city_start_id' => $this->integer()->notNull()->defaultValue(1), // 'city_id'
            'city_end_id' => $this->integer()->notNull()->defaultValue(1), // 'city_id'

            'cabins_json' => $this->json()->comment('Свободные каюты'),
            'timetable_json' => $this->json()->comment('Расписание'),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%cruises}}', 'Маршруты');

        $this->addForeignKey('fk_cruise_ship', '{{%cruises}}', 'ship_id', '{{%ships}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_port_start', '{{%cruises}}', 'port_start_id', '{{%ports}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_port_end', '{{%cruises}}', 'port_end_id', '{{%ports}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_city_start', '{{%cruises}}', 'city_start_id', '{{%cities}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_city_end', '{{%cruises}}', 'city_end_id', '{{%cities}}', 'id', 'CASCADE');

        $this->createIndex('idx_cruise_port_start', '{{%cruises}}', 'port_start_id');
        $this->createIndex('idx_cruise_port_end', '{{%cruises}}', 'port_end_id');
        $this->createIndex('idx_cruise_city_start', '{{%cruises}}', 'city_start_id');
        $this->createIndex('idx_cruise_city_end', '{{%cruises}}', 'city_end_id');

        $this->createIndex('idx_cruise_ship', '{{%cruises}}', 'ship_id');

        $this->createIndex('idx_cruise_slug', '{{%cruises}}', 'slug');

        $this->createIndex('idx_cruise_date_start-end', '{{%cruises}}', ['date_start', 'date_end']);
        $this->createIndex('idx_cruise_date_end', '{{%cruises}}', 'date_end');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_cruise_ship', '{{%cruises}}');
        $this->dropForeignKey('fk_cruise_port_start', '{{%cruises}}');
        $this->dropForeignKey('fk_cruise_port_end', '{{%cruises}}');
        $this->dropForeignKey('fk_cruise_city_start', '{{%cruises}}');
        $this->dropForeignKey('fk_cruise_city_end', '{{%cruises}}');

        $this->dropTable('{{%cruises}}');
    }
}

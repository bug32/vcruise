<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise}}`.
 */
class m241230_080833_create_cruise_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'route' => $this->text()->notNull(),
            'route_short' => $this->string()->notNull(),
            'description' => $this->text(),
            'type' => $this->string()->defaultValue(0)->comment('Круиз Речной или Морской'),
            'include' => $this->text()->comment('Включено'),
            'additional' => $this->text()->comment('Дополнительно'),
            'discounts' => $this->text()->comment('Скидки'),
            'map' => $this->string()->comment('ссылка на карту маршрута'),

            'date_start' => $this->date()->notNull(),
            'date_end' => $this->date()->notNull(),
            'date_start_timestamp' => $this->integer()->notNull(),
            'date_end_timestamp' => $this->integer()->notNull(),
            'days' => $this->smallInteger()->notNull(),
            'nights' => $this->smallInteger()->notNull(),

            'min_price' => $this->integer()->notNull(),
            'max_price' => $this->integer()->notNull(),
            'currency' => $this->string()->notNull()->defaultValue(1),

            'free_cabins' => $this->integer()->notNull()->defaultValue(0),

            'ship_id' => $this->integer()->notNull(),

            'port_start_id' => $this->integer()->notNull()->defaultValue(0), // 'port_id'
            'port_end_id' => $this->integer()->notNull()->defaultValue(0), // 'port_id'
            'dock_start' => $this->string(),

            'city_start_id' => $this->integer()->notNull()->defaultValue(0), // 'city_id'
            'city_end_id' => $this->integer()->notNull()->defaultValue(0), // 'city_id'

            'cabins_json' => $this->json()->comment('Свободные каюты'),
            'timetable_json' => $this->json()->comment('Расписание'),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%cruise}}', 'Маршруты');

        $this->addForeignKey('fk_cruise_ship', '{{%cruise}}', 'ship_id', '{{%ship}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_port_start', '{{%cruise}}', 'port_start_id', '{{%port}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_port_end', '{{%cruise}}', 'port_end_id', '{{%port}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_city_start', '{{%cruise}}', 'city_start_id', '{{%city}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_cruise_city_end', '{{%cruise}}', 'city_end_id', '{{%city}}', 'id', 'CASCADE');

        $this->createIndex('idx_cruise_port_start', '{{%cruise}}', 'port_start_id');
        $this->createIndex('idx_cruise_port_end', '{{%cruise}}', 'port_end_id');
        $this->createIndex('idx_cruise_city_start', '{{%cruise}}', 'city_start_id');
        $this->createIndex('idx_cruise_city_end', '{{%cruise}}', 'city_end_id');

        $this->createIndex('idx_cruise_ship', '{{%cruise}}', 'ship_id');

        $this->createIndex('idx_cruise_slug', '{{%cruise}}', 'slug');

        $this->createIndex('idx_cruise_date_start-end', '{{%cruise}}', ['date_start', 'date_end']);
        $this->createIndex('idx_cruise_date_end', '{{%cruise}}', 'date_end');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_cruise_ship', '{{%cruise}}');
        $this->dropForeignKey('fk_cruise_port_start', '{{%cruise}}');
        $this->dropForeignKey('fk_cruise_port_end', '{{%cruise}}');
        $this->dropForeignKey('fk_cruise_city_start', '{{%cruise}}');
        $this->dropForeignKey('fk_cruise_city_end', '{{%cruise}}');

        $this->dropTable('{{%cruise}}');
    }
}

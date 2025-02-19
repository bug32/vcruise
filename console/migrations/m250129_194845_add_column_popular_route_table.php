<?php

use yii\db\Migration;

/**
 * Class m250129_194845_add_column_popular_route_table
 */
class m250129_194845_add_column_popular_route_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('{{%popular_routes}}', 'is_filter', $this->tinyInteger(2)->defaultValue(0)->comment('Показывать в фильтре'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%popular_routes}}', 'is_filter');
        echo "m250129_194845_add_column_popular_route_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250129_194845_add_column_popular_route_table cannot be reverted.\n";

        return false;
    }
    */
}

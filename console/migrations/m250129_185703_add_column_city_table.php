<?php

use yii\db\Migration;

/**
 * Class m250129_185703_add_column_city_table
 */
class m250129_185703_add_column_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //  'is_filter' => $this->tinyInteger(2)->defaultValue(0)->comment('Показывать в фильтре'),
        $this->addColumn('{{%cities}}', 'is_filter', $this->tinyInteger(2)->defaultValue(0)->comment('Показывать в фильтре'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cities}}', 'is_filter');
        echo "m250129_185703_add_column_city_table cannot be reverted.\n";

        return FALSE;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250129_185703_add_column_city_table cannot be reverted.\n";

        return false;
    }
    */
}

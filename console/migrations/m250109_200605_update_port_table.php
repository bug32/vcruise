<?php

use yii\db\Migration;

/**
 * Class m250109_200605_update_port_table
 */
class m250109_200605_update_port_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('{{%ports}}', 'country_id', $this->integer()->notNull()->defaultValue(1));
        $this->addForeignKey('fk-ports-country', '{{%ports}}', 'country_id', '{{%countries}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%ports}}', 'country_id');
        $this->dropColumn('{{%ports}}', 'country_id');
        echo "m250109_200605_update_port_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250109_200605_update_port_table cannot be reverted.\n";

        return false;
    }
    */
}

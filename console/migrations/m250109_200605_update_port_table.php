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

        $this->addColumn('{{%port}}', 'country_id', $this->integer()->notNull()->defaultValue(0));
        $this->addForeignKey('fk-port-country', '{{%port}}', 'country_id', '{{%country}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%port}}', 'country_id');
        $this->dropColumn('{{%port}}', 'country_id');
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

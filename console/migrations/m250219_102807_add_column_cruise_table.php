<?php

use yii\db\Migration;

/**
 * Class m250219_102807_add_column_cruise_table
 */
class m250219_102807_add_column_cruise_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%cruises}}',
            'type_id',
            $this->integer()->defaultValue(1)->comment('Тип круиза')
        );



        $this->addForeignKey('fk_cruise_medias', '{{%cruises}}', 'id', '{{%cruise_medias}}', 'cruise_id', 'CASCADE');

        $this->addForeignKey(
            'fk_cruise_type',
            '{{%cruises}}',
            'type_id',
            '{{%cruise_types}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250219_102807_add_column_cruise_table cannot be reverted.\n";

        $this->dropForeignKey('fk_cruise_type', '{{%cruises}}');
        $this->dropColumn('{{%cruises}}', 'type_id');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250219_102807_add_column_cruise_table cannot be reverted.\n";

        return false;
    }
    */
}

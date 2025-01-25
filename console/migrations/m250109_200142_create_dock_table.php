<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dock}}`.
 */
class m250109_200142_create_dock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dock}}', [
            'id' => $this->primaryKey(),
            'port_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'address' => $this->string(),
            'coordinates' => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('dock', 'Портовые доки');

        $this->addForeignKey('fk_dock_port', 'dock', 'port_id', 'port', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dock}}');
    }
}

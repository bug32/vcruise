<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%docks}}`.
 */
class m250109_200142_create_dock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%docks}}', [
            'id' => $this->primaryKey(),
            'port_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'address' => $this->string(),
            'coordinates' => $this->string(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%docks}}', 'Портовые доки');

        $this->addForeignKey('fk_dock_port', '{{%docks}}', 'port_id', '{{%ports}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%docks}}');
    }
}

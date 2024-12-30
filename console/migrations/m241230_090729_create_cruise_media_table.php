<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_media}}`.
 */
class m241230_090729_create_cruise_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_media}}', [
            'id' => $this->primaryKey(),
            'cruise_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),

            'mime_type' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'size' => $this->integer()->notNull(),
            'priority' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cruise_media}}');
    }
}

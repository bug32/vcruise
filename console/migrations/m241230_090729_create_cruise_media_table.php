<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cruise_medias}}`.
 */
class m241230_090729_create_cruise_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cruise_medias}}', [
            'id' => $this->primaryKey(),
            'cruise_id' => $this->integer()->notNull(),
            'alt' => $this->string(),

            'name' => $this->string(),
            'mime_type' => $this->string(),
            'url' => $this->string()->notNull(),
            'size' => $this->integer()->defaultValue(0),
            'priority' => $this->integer()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cruise_medias}}');
    }
}

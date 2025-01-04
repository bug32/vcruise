<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cabin_type_media}}`.
 */
class m250103_191335_create_cabin_type_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabin_type_media}}', [
            'id' => $this->primaryKey(),
            'cabin_type_id' => $this->integer()->notNull(),

            'name' => $this->string(),
            'alt' => $this->string(),

            'mime_type' => $this->string(),
            'url' => $this->string(),
            'size' => $this->integer(),
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
        $this->dropTable('{{%cabin_type_media}}');
    }
}

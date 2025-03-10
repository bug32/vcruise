<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider_combinations}}`.
 */
class m250104_092600_create_provider_combination_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider_combinations}}', [
            'id'            => $this->primaryKey(),
            'provider_name' => $this->string()->notNull(),
            'foreign_id'    => $this->integer()->notNull(),
            'internal_id'   => $this->integer()->notNull(),
            'model_name'    => $this->string()->notNull()->comment('Модель связывания: ship, cruise, city и тд'),

            'created_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression(new \yii\db\Expression('NOW()')),
        ]);

        $this->addCommentOnTable('{{%provider_combinations}}', 'Комбинации ID сущностей провайдеров и ID внутри системы');

        $this->createIndex(
            'idx_provider_combination_all-foreign',
            '{{%provider_combinations}}',
            ['foreign_id', 'model_name', 'provider_name']
        );

        $this->createIndex(
            'idx_provider_combination_all-internal',
            '{{%provider_combinations}}',
             ['internal_id', 'model_name', 'provider_name']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%provider_combinations}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%budget_sheets}}`.
 */
class m241219_113837_create_budget_sheets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('budget_sheets', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-budget_sheets-user_id',
            'budget_sheets',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-budget_sheets-user_id', 'budget_sheets');
        $this->dropTable('budget_sheets');
    }
}

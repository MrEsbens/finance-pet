<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transactions}}`.
 */
class m241219_115540_create_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('transactions', [
            'id' => $this->primaryKey(),
            'sheet_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'amount' => $this->decimal()->notNull(),
            'transaction_date' => $this->date()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-transactions-sheet_id',
            'transactions',
            'sheet_id',
            'budget_sheets',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-transactions-category_id',
            'transactions',
            'category_id',
            'categories',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->createIndex(
            'idx-sheet_id-category_id',
            'transactions',
            [
                'sheet_id',
                'category_id',
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transactions-sheet_id', 'transactions');
        $this->dropForeignKey('fk-transactions-category_id', 'transactions');
        $this->dropIndex('idx-sheet_id-category_id', 'transactions');
        $this->dropTable('{{%transactions}}');
    }
}

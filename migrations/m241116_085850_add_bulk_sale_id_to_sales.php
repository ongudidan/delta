<?php

use yii\db\Migration;

/**
 * Class m241116_085850_add_bulk_sale_id_to_sales
 */
class m241116_085850_add_bulk_sale_id_to_sales extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create bulk_sale table
        $this->createTable('bulk_sale', [
            'id' => $this->primaryKey(),
            'reference_no' => $this->string()->defaultValue(null),
            'sale_date' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
        ]);

        // Create bulk_purchase table
        $this->createTable('bulk_purchase', [
            'id' => $this->primaryKey(),
            'reference_no' => $this->string()->defaultValue(null),
            'purchase_date' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
        ]);

        // Create bulk_expense table
        $this->createTable('bulk_expense', [
            'id' => $this->primaryKey(),
            'reference_no' => $this->string()->defaultValue(null),
            'expense_date' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
        ]);

        // Add nullable bulk_sale_id column to sales table
        $this->addColumn('sales', 'bulk_sale_id', $this->integer()->null());

        // Add nullable bulk_purchase_id column to sales table
        $this->addColumn('purchases', 'bulk_purchase_id', $this->integer()->null());

        // Add nullable bulk_expense_id column to sales table
        $this->addColumn('expenses', 'bulk_expense_id', $this->integer()->null());

        // Create foreign key relationship
        $this->addForeignKey(
            'fk-sales-bulk_sale_id',
            'sales',
            'bulk_sale_id',
            'bulk_sale',
            'id',
            'SET NULL', // This ensures that if a bulk_sale is deleted, the reference is set to null.
            'CASCADE'   // This ensures cascading deletion for bulk_sale records.
        );

        // Create foreign key relationship
        $this->addForeignKey(
            'fk-purchases-bulk_purchase_id',
            'purchases',
            'bulk_purchase_id',
            'bulk_purchase',
            'id',
            'SET NULL', // This ensures that if a bulk_purchase is deleted, the reference is set to null.
            'CASCADE'   // This ensures cascading deletion for bulk_purchase records.
        );

        // Create foreign key relationship
        $this->addForeignKey(
            'fk-expenses-bulk_expense_id',
            'expenses',
            'bulk_expense_id',
            'bulk_expense',
            'id',
            'SET NULL', // This ensures that if a bulk_expense is deleted, the reference is set to null.
            'CASCADE'   // This ensures cascading deletion for bulk_expense records.
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key and bulk_expense_id column
        $this->dropForeignKey('fk-expenses-bulk_expense_id', 'expenses');
        $this->dropColumn('expenses', 'bulk_expense_id');

        // Drop foreign key and bulk_purchase_id column
        $this->dropForeignKey('fk-purchases-bulk_purchase_id', 'purchases');
        $this->dropColumn('purchases', 'bulk_purchase_id');

        // Drop foreign key and bulk_sale_id column
        $this->dropForeignKey('fk-sales-bulk_sale_id', 'sales');
        $this->dropColumn('sales', 'bulk_sale_id');

        // Drop bulk_expense table
        $this->dropTable('bulk_expense');

        // Drop bulk_purchase table
        $this->dropTable('bulk_purchase');

        // Drop bulk_sale table
        $this->dropTable('bulk_sale');
    }
}

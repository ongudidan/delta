<?php

use yii\db\Migration;

/**
 * Class m240812_142614_create_delta_tables
 */
class m240812_142614_create_delta_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create payment_methods table first
        $this->createTable('{{%payment_methods}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
        ]);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'phone' => $this->string(),
            'gender' => $this->string(),
            'verification_token' => $this->string()->defaultValue(null),

            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Create categories table
        $this->createTable('{{%categories}}', [
            'category_id' => $this->primaryKey(),
            'category_name' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
        ]);

        // Create sub_category table
        $this->createTable('{{%sub_categories}}', [
            'sub_category_id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'sub_category_name' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%categories}} ([[category_id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create products table
        $this->createTable('{{%products}}', [
            'product_id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'product_name' => $this->string(),
            'selling_price' => $this->decimal()->defaultValue(null),
            'product_number' => $this->string(),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%categories}} ([[category_id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create sales table
        $this->createTable('{{%sales}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'sell_price' => $this->decimal()->notNull(),
            'total_amount' => $this->decimal()->notNull(),
            'sale_date' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'payment_method_id' => $this->integer()->notNull(),
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%products}} ([[product_id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            // 'FOREIGN KEY ([[payment_method_id]]) REFERENCES {{%payment_methods}} ([[id]])' .
            //     $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create purchases table
        $this->createTable('{{%purchases}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'buying_price' => $this->decimal()->notNull(),
            'total_cost' => $this->decimal()->notNull(),
            'purchase_date' => $this->integer()->notNull(),
            'payment_method_id' => $this->integer()->notNull(),
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%products}} ([[product_id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            // 'FOREIGN KEY ([[payment_method_id]]) REFERENCES {{%payment_methods}} ([[id]])' .
            //     $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create expense_categories table
        $this->createTable('{{%expense_categories}}', [
            'expense_category_id' => $this->primaryKey(),
            'category_name' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
        ]);

        // Create expenses table
        $this->createTable('{{%expenses}}', [
            'expense_id' => $this->primaryKey(),
            'expense_category_id' => $this->integer()->notNull(),
            'amount' => $this->decimal()->notNull(),
            'created_at' => $this->integer()->notNull()->defaultValue(null),
            'updated_at' => $this->integer()->notNull()->defaultValue(null),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'payment_method_id' => $this->integer()->notNull(),
            'FOREIGN KEY ([[expense_category_id]]) REFERENCES {{%expense_categories}} ([[expense_category_id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[payment_method_id]]) REFERENCES {{%payment_methods}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create RBAC tables in correct order
        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string()->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ]);

        // Create auth_item table
        $this->createTable('{{%auth_item}}', [
            'name' => $this->string()->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'KEY rule_name (rule_name)',
            'KEY type (type)',
        ]);

        // seed auth items
        $this->batchInsert(
            '{{%auth_item}}',
            ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
            [
                [' Categories Manage', 1, 'Manage Categories', NULL, NULL, NULL, NULL],
                ['Auth Assignment Manage', 1, 'can manage all auth assignment', NULL, NULL, 1724680716, 1724680716],
                ['Auth Item Child Manage', 1, 'can manage all auth item child', NULL, NULL, 1724680918, 1724680918],
                ['Auth Item Manage', 1, 'can manage all auth items', NULL, NULL, 1724680521, 1724680521],
                ['Auth Rule Manage', 1, 'can manage all auth rules', NULL, NULL, 1724681088, 1724681088],
                ['auth-assignment-create', 1, 'can create auth assignment', NULL, NULL, 1724680738, 1724680738],
                ['auth-assignment-delete', 1, 'can delete auth assignment', NULL, NULL, 1724680804, 1724680804],
                ['auth-assignment-update', 1, 'can update auth assignment', NULL, NULL, 1724680760, 1724680760],
                ['auth-assignment-view', 1, 'can view auth assignment', NULL, NULL, 1724680780, 1724680780],
                ['auth-item-child-create', 1, 'can create auth item child', NULL, NULL, 1724680940, 1724680940],
                ['auth-item-child-delete', 1, 'can delete auth item child', NULL, NULL, 1724681006, 1724681006],
                ['auth-item-child-update', 1, 'can update auth item child', NULL, NULL, 1724680958, 1724680958],
                ['auth-item-child-view', 1, 'can view auth item child', NULL, NULL, 1724680979, 1724680979],
                ['auth-item-create', 1, 'can create auth item', NULL, NULL, 1724680543, 1724680543],
                ['auth-item-delete', 1, 'can delete auth item', NULL, NULL, 1724680610, 1724680610],
                ['auth-item-update', 1, 'can update auth item', NULL, NULL, 1724680566, 1724680566],
                ['auth-item-view', 1, 'can view auth item', NULL, NULL, 1724680587, 1724680587],
                ['auth-rule-create', 1, 'can create auth rule', NULL, NULL, 1724681110, 1724681110],
                ['auth-rule-delete', 1, 'can delete auth rule', NULL, NULL, 1724681167, 1724681167],
                ['auth-rule-update', 1, 'can update auth rule', NULL, NULL, 1724681131, 1724681131],
                ['auth-rule-view', 1, 'can view auth rule', NULL, NULL, 1724681151, 1724681151],
                ['category-create', 1, 'create product category', NULL, NULL, NULL, NULL],
                ['category-delete', 1, 'can delete product category', NULL, NULL, NULL, NULL],
                ['category-update', 1, 'can update product category', NULL, NULL, NULL, NULL],
                ['category-view', 1, 'can view product category', NULL, NULL, NULL, NULL],
                ['Expense Categories Manage', 1, 'Manage expense categories', NULL, NULL, NULL, NULL],
                ['expense-category-create', 1, 'can create expense category', NULL, NULL, NULL, NULL],
                ['expense-category-delete', 1, 'can delete expense category', NULL, NULL, NULL, NULL],
                ['expense-category-update', 1, 'can update expense category', NULL, NULL, NULL, NULL],
                ['expense-category-view', 1, 'can view expense category', NULL, NULL, NULL, NULL],
                ['expense-create', 1, 'can delete expense', NULL, NULL, NULL, NULL],
                ['expense-delete', 1, 'can delete expense', NULL, NULL, NULL, NULL],
                ['expense-update', 1, 'can delete expense', NULL, NULL, NULL, NULL],
                ['expense-view', 1, 'can delete expense', NULL, NULL, NULL, NULL],
                ['Expenses Manage', 1, 'Manage expenses', NULL, NULL, NULL, NULL],
                ['Inventories Manage', 1, 'Manage inventories', NULL, NULL, NULL, NULL],
                ['inventory-view', 1, 'can view inventory', NULL, NULL, NULL, NULL],
                ['product-create', 1, 'can create product', NULL, NULL, 1724640005, 1724640005],
                ['product-delete', 1, 'can delete product', NULL, NULL, 1724640044, 1724640044],
                ['product-update', 1, 'can create product', NULL, NULL, 1724640026, 1724640026],
                ['product-view', 1, 'can view product', NULL, NULL, 1724639987, 1724639987],
                ['Products Manage', 1, 'Manage products', NULL, NULL, NULL, NULL],
                ['purchase-create', 1, 'can create a purchase', NULL, NULL, 1724430733, 1724430733],
                ['purchase-delete', 1, 'can delete purchase', NULL, NULL, 1724430795, 1724430795],
                ['purchase-update', 1, 'can update purchase', NULL, NULL, 1724430762, 1724430762],
                ['purchase-view', 1, 'can view purchase', NULL, NULL, 1724430777, 1724430777],
                ['Purchases Manage', 1, 'Manage purchases', NULL, NULL, NULL, NULL],
                ['sale-create', 1, 'can create sale', NULL, NULL, 1724660463, 1724660463],
                ['sale-delete', 1, 'can delete sale', NULL, NULL, NULL, NULL],
                ['sale-update', 1, 'can update sale', NULL, NULL, 1724660487, 1724660487],
                ['sale-view', 1, 'can view sale', NULL, NULL, NULL, NULL],
                ['Sales Manage', 1, 'Manage Sales', NULL, NULL, NULL, NULL],
                ['user-create', 1, 'can create user\r\n', NULL, NULL, 1725025635, 1725025635],
                ['user-delete', 1, 'can delete user', NULL, NULL, 1725025677, 1725025677],
                ['user-update', 1, 'can update user', NULL, NULL, 1725025647, 1725025647],
                ['user-view', 1, 'can view user', NULL, NULL, 1725025661, 1725025661],
                ['Users Manage', 1, 'Can perform CRUD on users', NULL, NULL, NULL, NULL],
            ]
        );

        // Create auth_item_child table
        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string()->notNull(),
            'child' => $this->string()->notNull(),
            'PRIMARY KEY (parent, child)',
            'KEY child (child)',
            'FOREIGN KEY ([[parent]]) REFERENCES {{%auth_item}} ([[name]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[child]]) REFERENCES {{%auth_item}} ([[name]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        //seed auth item child
        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            [' Categories Manage', 'category-create'],
            [' Categories Manage', 'category-delete'],
            [' Categories Manage', 'category-update'],
            [' Categories Manage', 'category-view'],
            ['Auth Assignment Manage', 'auth-assignment-create'],
            ['Auth Assignment Manage', 'auth-assignment-delete'],
            ['Auth Assignment Manage', 'auth-assignment-update'],
            ['Auth Assignment Manage', 'auth-assignment-view'],
            ['Auth Item Child Manage', 'auth-item-child-create'],
            ['Auth Item Child Manage', 'auth-item-child-delete'],
            ['Auth Item Child Manage', 'auth-item-child-update'],
            ['Auth Item Child Manage', 'auth-item-child-view'],
            ['Auth Item Manage', 'auth-item-create'],
            ['Auth Item Manage', 'auth-item-delete'],
            ['Auth Item Manage', 'auth-item-update'],
            ['Auth Item Manage', 'auth-item-view'],
            ['Auth Rule Manage', 'auth-rule-create'],
            ['Auth Rule Manage', 'auth-rule-delete'],
            ['Auth Rule Manage', 'auth-rule-update'],
            ['Auth Rule Manage', 'auth-rule-view'],
            ['Expense Categories Manage', 'expense-category-create'],
            ['Expense Categories Manage', 'expense-category-delete'],
            ['Expense Categories Manage', 'expense-category-update'],
            ['Expense Categories Manage', 'expense-category-view'],
            ['Expenses Manage', 'expense-create'],
            ['Expenses Manage', 'expense-delete'],
            ['Expenses Manage', 'expense-update'],
            ['Expenses Manage', 'expense-view'],
            ['Inventories Manage', 'inventory-view'],
            ['Products Manage', 'product-create'],
            ['Products Manage', 'product-delete'],
            ['Products Manage', 'product-update'],
            ['Products Manage', 'product-view'],
            ['Purchases Manage', 'purchase-create'],
            ['Purchases Manage', 'purchase-delete'],
            ['Purchases Manage', 'purchase-update'],
            ['Purchases Manage', 'purchase-view'],
            ['Sales Manage', 'sale-create'],
            ['Sales Manage', 'sale-delete'],
            ['Sales Manage', 'sale-update'],
            ['Sales Manage', 'sale-view'],
            ['Users Manage', 'user-create'],
            ['Users Manage', 'user-delete'],
            ['Users Manage', 'user-update'],
            ['Users Manage', 'user-view'],
        ]);

        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string()->notNull(),
            'user_id' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY ([[item_name]]) REFERENCES {{%auth_item}} ([[name]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // seed auth assignment
        $this->batchInsert('{{%auth_assignment}}', ['item_name', 'user_id', 'created_at'], [
            [' Categories Manage', '1', 1724431013],
            [' Categories Manage', '2', 1724681638],
            ['Auth Assignment Manage', '2', 1724681638],
            ['Auth Item Child Manage', '2', 1724681638],
            ['Auth Item Manage', '2', 1724681638],
            ['Auth Rule Manage', '2', 1724681638],
            ['Expense Categories Manage', '1', 1724431013],
            ['Expense Categories Manage', '2', 1724681638],
            ['Expenses Manage', '1', 1724431013],
            ['Expenses Manage', '2', 1724681638],
            ['Inventories Manage', '1', 1724431013],
            ['Inventories Manage', '2', 1724681638],
            ['Products Manage', '1', 1724431013],
            ['Products Manage', '2', 1724681638],
            ['Purchases Manage', '1', 1724431013],
            ['Purchases Manage', '2', 1724681638],
            ['Sales Manage', '1', 1724431013],
            ['Sales Manage', '2', 1724431013],
            ['Users Manage', '1', 1724431013],
            ['Users Manage', '2', 1724681638],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop RBAC tables
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_rule}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_assignment}}');

        // Drop other tables
        $this->dropTable('{{%inventory}}');
        $this->dropTable('{{%purchases}}');
        $this->dropTable('{{%sales}}');
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%sub_categories}}');
        $this->dropTable('{{%categories}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%expenses}}');
        $this->dropTable('{{%expense_categories}}');

        // Drop payment_methods table
        $this->dropTable('{{%payment_methods}}');
    }

    protected function buildFkClause($delete = '', $update = '')
    {
        return implode(' ', ['', $delete, $update]);
    }
}

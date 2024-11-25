<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_expense_categories}}`.
 */
class m240814_180440_create_seed_expense_categories_table extends Migration
{
   /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $categories = [
            ['Advertising expenses', 'Costs associated with promoting the business, including ads and marketing materials.'],
            ['Meals and entertainment expenses', 'Expenses for meals and entertainment related to business activities.'],
            ['Payroll', 'Payments made to employees, including salaries, wages, and bonuses.'],
            ['Rent, utilities and phone', 'Costs for leasing space, utility services, and telephone lines.'],
            ['Travel expenses', 'Costs associated with business travel, including transportation and lodging.'],
            ['Employee benefits', 'Expenses for benefits provided to employees, such as health insurance and retirement plans.'],
            ['Transportation', 'Costs for vehicle maintenance and fuel used for business purposes.'],
            ['Office expenses', 'Expenses related to running an office, including furniture, supplies, and equipment.'],
            ['Professional services', 'Fees paid to professionals such as consultants, lawyers, and accountants.'],
            ['Loan and interest payments', 'Repayments of business loans and interest charges.'],
            ['Insurance', 'Premiums for various types of business insurance.'],
            ['Education', 'Costs for employee training and professional development.'],
            ['Casualty losses', 'Expenses related to loss or damage of business assets due to unforeseen events.'],
            ['Charitable contributions', 'Donations made to charitable organizations.'],
            ['Gifts', 'Expenses for gifts given to clients, employees, or other stakeholders.'],
            ['Postage and mailing', 'Costs associated with sending mail and packages.'],
            ['Maintenance and repair costs', 'Expenses for maintaining and repairing equipment and facilities.'],
        ];

        $rows = array_map(function($category) {
            return [$category[0], $category[1], time(), time()];
        }, $categories);

        $this->batchInsert('{{%expense_categories}}', ['category_name', 'description', 'created_at', 'updated_at'], $rows);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%expense_categories}}', [
            'category_name' => [
                'Advertising expenses',
                'Meals and entertainment expenses',
                'Payroll',
                'Rent, utilities and phone',
                'Travel expenses',
                'Employee benefits',
                'Transportation',
                'Office expenses',
                'Professional services',
                'Loan and interest payments',
                'Insurance',
                'Education',
                'Casualty losses',
                'Charitable contributions',
                'Gifts',
                'Postage and mailing',
                'Maintenance and repair costs',
            ],
        ]);
    }
}

<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_products}}`.
 */
class m240813_132002_create_seed_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Load the spreadsheet file
        $spreadsheet = IOFactory::load('DELTA 2024-1.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Iterate through the rows and insert them into the products table
        foreach ($data as $index => $row) {
            // Skip rows before row 5 (index 4)
            if ($index < 4) {
                continue;
            }

            // Generate a random product number
            $randomNumber = rand(10000, 99999); // Random number between 10000 and 99999
            $productNumber = 'PN-' . $randomNumber; // Example: 'PN-12345'

            // Set product name and description from the row data
            $productName = $row[0] ?? 'Unnamed Product'; // Default to 'Unnamed Product' if null
            $description = 'Description for ' . ($row[2] ?? 'Unnamed Product'); // Default description if null

            // Generate random category_id between 1 and 8
            $randomCategoryId = rand(1, 8);

            $this->insert('{{%products}}', [
                'category_id' => $randomCategoryId,
                'product_name' => $productName,
                'selling_price' => $row[2] ?? null, // Default to null if not set
                'product_number' => $productNumber,
                'description' => $description,
                'created_at' => time(), // Current timestamp
                'updated_at' => time(), // Current timestamp
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Optionally, add code here to remove the imported data
        // For example, you could delete the rows you inserted
    }
}

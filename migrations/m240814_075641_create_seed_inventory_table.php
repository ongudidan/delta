<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_inventory}}`.
 */
class m240814_075641_create_seed_inventory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $spreadsheet = IOFactory::load('DELTA 2024.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Extract data from the sheet and prepare for insertion
        $inventoryData = $this->prepareInventoryData($data);

        // Insert data into the inventory table
        $this->batchInsert('{{%inventory}}', ['product_id', 'quantity', 'created_at', 'updated_at'], $inventoryData);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Optionally, you can remove the inserted data
        // For example, delete all rows where product_id is between 1 and 743
        $this->delete('{{%inventory}}', ['between', 'product_id', 1, 743]);
    }

    /**
     * Prepares the inventory data from the spreadsheet.
     * @param array $data
     * @return array
     */
    protected function prepareInventoryData($data)
    {
        $inventoryData = [];
        $currentTime = time();
        $productIdCounter = 1;

        // Start from row 5 (index 4) if the header is before this row
        foreach ($data as $index => $row) {
            if ($index < 4) {
                continue; // Skip rows before row 5
            }

            // Use the product ID from the previous logic
            $productId = $productIdCounter++;
            $quantity = 0; // Default to 0 if quantity is not set

            $inventoryData[] = [
                $productId,
                $quantity,
                $currentTime,
                $currentTime
            ];

            // Stop if product ID exceeds 743 (if necessary)
            if ($productId > 698) {
                break;
            }
        }

        return $inventoryData;
    }
}

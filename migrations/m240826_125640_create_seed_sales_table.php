<?php

use app\models\Products;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_sales}}`.
 */
class m240826_125640_create_seed_sales_table extends Migration
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
            $productName= $row[0];
            $quantity=intval($row[8]);
            $sellingPrice=intval($row[2]);
            $totalCost= $quantity * $sellingPrice;
            $product= Products::find()->where(['product_name'=> $productName])->one();
            $productId=$product->product_id;
            $sellDate= strtotime('2024-08-24');

            if($quantity<=0){
                continue;
            }

            $this->insert('{{%sales}}', [
                'product_id' => $productId,
                'quantity' => $quantity ?? null, // Default to null if not set
                'sell_price' => $sellingPrice ?? null, // Default to null if not set
                'total_amount' => intval($totalCost) ?? null, // Default to null if not set
                'sale_date' =>$sellDate, // Current timestamp
                'payment_method_id' => 2, // Current timestamp
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%seed_sales}}');
    }
}

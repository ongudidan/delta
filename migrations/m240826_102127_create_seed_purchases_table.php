<?php

use app\models\Products;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_purchases}}`.
 */
class m240826_102127_create_seed_purchases_table extends Migration
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
            $quantity=intval($row[3]);
            $buyingPrice=intval($row[1]);
            $totalCost= $quantity * $buyingPrice;
            $product= Products::find()->where(['product_name'=> $productName])->one();
            $productId=$product->product_id;
            $purchaseDate= strtotime('2024-08-24');

            if($quantity<=0){
                continue;
            }

            $this->insert('{{%purchases}}', [
                'product_id' => $productId,
                'quantity' => $quantity ?? null, // Default to null if not set
                'buying_price' => $buyingPrice ?? null, // Default to null if not set
                'total_cost' => intval($totalCost) ?? null, // Default to null if not set
                'purchase_date' =>$purchaseDate, // Current timestamp
                'payment_method_id' => 2, // Current timestamp
            ]);
        }

        //////////////////////////////////////
          // Iterate through the rows and insert them into the products table
          foreach ($data as $index => $row) {
            // Skip rows before row 5 (index 4)
            if ($index < 4) {
                continue;
            }
            $productName= $row[0];
            $quantity=intval($row[5]);
            $buyingPrice=intval($row[1]);
            $totalCost= $quantity * $buyingPrice;
            $product= Products::find()->where(['product_name'=> $productName])->one();
            $productId=$product->product_id;
            $purchaseDate= strtotime('2024-08-24');


            if($quantity<=0){
                continue;
            }

            $this->insert('{{%purchases}}', [
                'product_id' => intval($productId),
                'quantity' => $quantity ?? null, // Default to null if not set
                'buying_price' => $buyingPrice ?? null, // Default to null if not set
                'total_cost' => intval($totalCost) ?? null, // Default to null if not set
                'purchase_date' =>$purchaseDate, // Current timestamp
                'payment_method_id' => 2, // Current timestamp
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->dropTable('{{%seed_purchases}}');
    }
}

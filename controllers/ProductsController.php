<?php

namespace app\controllers;

use app\models\Inventory;
use app\models\Products;
use app\models\ProductsSearch;
use app\models\Sales;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        // 'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Products models.
     *
     * @return string
     */
    public function actionIndex()
    {

            $model = new Products();
            if ($this->request->isPost) {
                $model->createProduct($this->request);
            } else {
                $model->loadDefaultValues();
            }

            $searchModel = new ProductsSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

    }

    // public function menus(){
    //     $menuItems=[
    //         [ 'name'=>'All Products','id'=> 'home-tab4', 'url'=>'/products/index'],
    //         ['name'=>'All Categories','id'=>'profile-tab4', 'url'=>'/categories/index'],
    //         ['name'=>'All Sales','id'=>'profile-tab5', 'url'=>'/sales/index'],
    //         ['name'=>'New Product','id'=>'contact-tab6', 'url'=>'/products/create']
    //     ];
    //     return $menuItems;
    // }

    /**
     * Displays a single Products model.
     * @param int $product_id Product ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($product_id)
    {

            return $this->render('view', [
                'model' => $this->findModel($product_id),
            ]);

    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('product-create')) {

            $model = new Products();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    // Create the inventory record
                    $inventory = new Inventory();
                    $inventory->product_id = $model->product_id;
                    $inventory->quantity = 0;
                    $inventory->updated_at = $model->created_at; // Default quantity
                    $inventory->save(); // Save the inventory record

                    return $this->redirect(['view', 'product_id' => $model->product_id]);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }


    public function actionCreateSale()
    {
            $model = new Sales();

            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    // Calculate and set total_amount
                    $model->total_amount = $model->getTotalAmount();

                    if (date('Y-m-d', strtotime($model->sale_date)) === date('Y-m-d')) {
                        $model->sale_date = time();
                    } else {
                        $model->sale_date = strtotime($model->sale_date);
                    }

                    // Remove inventory check and update
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Saved successfully');
                        return $this->redirect(['/products/index', 'id' => $model->id]);
                    }
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create-sale', [
                'model' => $model,
            ]);
  
    }


    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $product_id Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($product_id)
    {
        if (Yii::$app->user->can('product-update')) {

            $model = $this->findModel($product_id);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                // Check if an inventory record exists for the product
                // $inventory = Inventory::findOne(['product_id' => $product_id]);
                // if ($inventory === null) {
                //     // Create the inventory record if it does not exist
                //     $inventory = new Inventory();
                //     $inventory->product_id = $model->product_id;
                //     $inventory->quantity = 0; // Default quantity
                //     $inventory->updated_at = $model->updated_at; // Updated timestamp
                //     $inventory->save(); // Save the inventory record
                // } else {
                //     // Update the existing inventory record if needed
                //     $inventory->updated_at = $model->updated_at; // Update timestamp
                //     $inventory->save(); // Save the updated inventory record
                // }

                Yii::$app->session->setFlash('success', $model->product_name.' updated successfully');

                return $this->redirect(['index', 'product_id' => $model->product_id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $product_id Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($product_id)
    {
        if (Yii::$app->user->can('product-delete')) {

            $model = $this->findModel($product_id);

            // Delete the corresponding inventory record
            $inventory = Inventory::findOne(['product_id' => $product_id]);
            if ($inventory !== null) {
                $inventory->delete();
            }

            // Delete the product
            $model->delete();

            return $this->redirect(['index']);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $product_id Product ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_id)
    {
        if (($model = Products::findOne(['product_id' => $product_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace app\modules\dashboard\controllers;

use app\models\ExpenseCategories;
use app\modules\dashboard\models\ExpenseCategoriesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpenseCategoriesController implements the CRUD actions for ExpenseCategories model.
 */
class ExpenseCategoriesController extends Controller
{
    public $layout = 'DashboardLayout';

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
     * Lists all ExpenseCategories models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExpenseCategoriesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpenseCategories model.
     * @param int $expense_category_id Expense Category ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($expense_category_id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($expense_category_id),
        ]);
    }

    /**
     * Creates a new ExpenseCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExpenseCategories();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Expense Category created successfully!');

                return $this->redirect(['index', 'expense_category_id' => $model->expense_category_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExpenseCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $expense_category_id Expense Category ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($expense_category_id)
    {
        $model = $this->findModel($expense_category_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Expense Category updated successfully!');

            return $this->redirect(['index', 'expense_category_id' => $model->expense_category_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExpenseCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $expense_category_id Expense Category ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($expense_category_id)
    {
        $this->findModel($expense_category_id)->delete();

        Yii::$app->session->setFlash('success', 'Expense Category deleted successfully!');


        return $this->redirect(['index']);
    }

    /**
     * Finds the ExpenseCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $expense_category_id Expense Category ID
     * @return ExpenseCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($expense_category_id)
    {
        if (($model = ExpenseCategories::findOne(['expense_category_id' => $expense_category_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

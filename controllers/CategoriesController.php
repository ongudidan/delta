<?php

namespace app\controllers;

use app\models\Categories;
use app\models\CategoriesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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
     * Lists all Categories models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('category-view')) {
            $model = new Categories();

            if ($this->request->isPost) {
                $model->createCategory($this->request);
            } else {
                $model->loadDefaultValues();
            }

            $searchModel = new CategoriesSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Displays a single Categories model.
     * @param int $category_id Category ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($category_id)
    {
        if (Yii::$app->user->can('category-view')) {
            return $this->render('view', [
                'model' => $this->findModel($category_id),
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('category-create')) {

            $model = new Categories();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    Yii::$app->session->setFlash('success', 'Category created successfully');

                    return $this->redirect(['view', 'category_id' => $model->category_id]);
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

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $category_id Category ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($category_id)
    {
        if (Yii::$app->user->can('category-update')) {

            $model = $this->findModel($category_id);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Category updated successfully');

                return $this->redirect(['view', 'category_id' => $model->category_id]);
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
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $category_id Category ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($category_id)
    {
        if (Yii::$app->user->can('category-delete')) {

            $this->findModel($category_id)->delete();
            Yii::$app->session->setFlash('success', 'Category deleted successfully');


            return $this->redirect(['index']);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $category_id Category ID
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($category_id)
    {
        if (($model = Categories::findOne(['category_id' => $category_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

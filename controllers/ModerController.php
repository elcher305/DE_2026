<?php

namespace app\controllers;

use app\models\Application;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModerController implements the CRUD actions for Application model.
 */
class ModerController extends Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    public function actionIndex()
    {
        $query = Application::find()->with('user');



        $dataProvider = new ActiveDataProvider([
            'query' => Application::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);


    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Application();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateStatus($id, $status)
    {
        $model = $this->findModel($id);

        // Проверяем, не обработана ли уже заявка
        if ($model->status !== 'new') {
            Yii::$app->session->setFlash('error', 'Эта заявка уже обработана');
            return $this->redirect(['index']);
        }

        if ($status === 'confirmed') {
            $model->status = 'confirmed';
            $model->rejection_reason = null;
            $model->save();
            Yii::$app->session->setFlash('success', 'Заявка подтверждена');
        } elseif ($status === 'rejected') {
            // Для отклонения нужно указать причину
            $reason = Yii::$app->request->post('reason');
            if (!$reason) {
                Yii::$app->session->setFlash('error', 'Укажите причину отклонения');
                return $this->redirect(['index']);
            }
            $model->status = 'rejected';
            $model->rejection_reason = $reason;
            $model->save();
            Yii::$app->session->setFlash('success', 'Заявка отклонена');
        }

        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Application::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

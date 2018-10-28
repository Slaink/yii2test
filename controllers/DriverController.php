<?php

namespace app\controllers;

use app\models\DriverBus;
use kartik\grid\EditableColumnAction;
use Yii;
use app\models\Driver;
use app\models\DriverSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DriverController implements the CRUD actions for Driver model.
 */
class DriverController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'editactive' => [
                'class' => EditableColumnAction::class,
                'modelClass' => Driver::class,
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return $model->$attribute ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
                'showModelErrors' => true,
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createDriver'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'editactive'],
                        'roles' => ['updateDriver'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['deleteDriver'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Driver models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DriverSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Driver model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Driver();
        $post_data = Yii::$app->request->post();

        if ($model->load($post_data)) {
            $file = UploadedFile::getInstance($model, 'photo');
            $file_name = '';
            if ($file) {
                $file_name = md5(date('dmYHi') . $file->name . Yii::$app->user->id) . '.' . $file->extension;
                $resource = @fopen($file->tempName, 'r');
                Image::resize($resource, 500, 500)
                    ->save(Yii::getAlias('@app/web/uploads/' . $file_name));
            }
            $model->photo = $file_name;
            if ($model->save() && !empty($post_data['Driver']['buses'])) {
                foreach ($post_data['Driver']['buses'] as $bus_id) {
                    $driver_bus = new DriverBus();
                    $driver_bus->driver_id = $model->id;
                    $driver_bus->bus_id = $bus_id;
                    $driver_bus->save();
                }
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Driver model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post_data = Yii::$app->request->post();

        if ($model->load($post_data)) {
            $file = UploadedFile::getInstance($model, 'photo');
            $file_name = '';
            if (!empty($file)) {
                $file_name = md5(date('dmYHi') . $file->name . Yii::$app->user->id) . '.' . $file->extension;
                $resource = @fopen($file->tempName, 'r');
                Image::resize($resource, 500, 500)
                    ->save(Yii::getAlias('@app/web/uploads/' . $file_name));
            }
            if (!empty($file_name)) {
                $model->photo = $file_name;
            } else {
                $model->photo = $model->getOldAttribute('photo');
            }
            $buses_ids = $model->getBusesIds();
            $new_buses_ids = empty($post_data['Driver']['buses']) ? [] : $post_data['Driver']['buses'];
            DriverBus::deleteAll(['bus_id' => array_diff($buses_ids, $new_buses_ids), 'driver_id' => $model->id]);
            if ($model->save() && !empty($save_buses_ids = array_diff($new_buses_ids, $buses_ids))) {
                foreach ($save_buses_ids as $bus_id) {
                    $driver_bus = new DriverBus();
                    $driver_bus->driver_id = $model->id;
                    $driver_bus->bus_id = $bus_id;
                    $driver_bus->save();
                }
            }

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Driver model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Driver model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Driver the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Driver::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

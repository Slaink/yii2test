<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DriverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Drivers');
$this->params['breadcrumbs'][] = $this->title;
$distance = Yii::$app->request->get('distance');
?>
<div id="map" style="display: none"></div>
<div class="driver-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <form id="distance-form" class="form-inline" method="GET" role="form">
        <?= Html::hiddenInput('distance', $distance) ?>
        <?= Html::input('text', 'from', Yii::$app->request->get('from'), ['placeholder' => Yii::t('app', 'From'), 'class' => 'form-control']) ?>
        <?= Html::input('text', 'to', Yii::$app->request->get('to'), ['placeholder' => Yii::t('app', 'To'), 'class' => 'form-control']) ?>
        <?= Html::button(Yii::t('app', 'Count'), ['class' => 'btn btn-primary', 'id' => 'count_submit']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> ' . Yii::t('app', 'Reset'), \yii\helpers\Url::to('/driver/index'), ['class' => 'btn btn-default',]) ?>
    </form>

    <p></p>

    <? if (Yii::$app->user->can('createDriver')) { ?>
        <p>
            <?= Html::a(Yii::t('app', 'Create Driver'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <? } ?>

    <?php echo GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '36px',
                'header' => '',
                'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'is_active',
                'editableOptions' => [
                    'header' => '',
                    'inputType' => Editable::INPUT_WIDGET,
                    'widgetClass' => Editable::INPUT_CHECKBOX_X,
                    'formOptions' => ['action' => ['/driver/editactive']],
                    'options' => ['pluginOptions' => ['threeState' => false]],
                ],
                'hAlign' => 'right',
                'vAlign' => 'middle',
                'width' => '7%',
                'format' => ['boolean'],
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'data' => ['' => 'Все', 1 => 'Да', 0 => 'Нет'],
                ],
            ],
            'summary',
            [
                'attribute' => 'photo',
                'value' => function ($model, $key, $index, $widget) {
                    return "<img width='150px' src='/uploads/{$model->photo}'>";
                },
                'vAlign' => 'middle',
                'format' => 'raw',
                'filter' => false,
            ],
            [
                'attribute' => 'birthday',
                'label' => Yii::t('app', 'Age'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->getAge();
                },
                'vAlign' => 'middle',
                'format' => 'raw',
                'width' => '50px',
            ],
            [
                'attribute' => 'buses',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->getBusesInfo();
                },
                'vAlign' => 'middle',
                'format' => 'raw',
            ],
            [
                'label' => Yii::t('app', 'distance_time'),
                'value' => function ($model, $key, $index, $widget) use ($distance) {
                    return $model->getDistanceTime($distance);
                },
                'visible' => !empty($distance),
                'vAlign' => 'middle',
                'format' => 'raw',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdownOptions' => ['class' => 'float-right'],
                'visibleButtons' => [
                    'update' => Yii::$app->user->can('updateDriver'),
                    'delete' => Yii::$app->user->can('deleteDriver'),
                    'view' => false,
                ],
            ],
        ],
        'pjax' => true,
        'bordered' => true,
        'toggleDataOptions' => ['minCount' => 20],
        'itemLabelSingle' => Yii::t('app', 'driver'),
        'itemLabelPlural' => Yii::t('app', 'drivers'),
    ]); ?>
</div>
<? $this->registerJsFile('//api-maps.yandex.ru/2.1/?lang=ru_RU', [], 'ymaps.js') ?>
<? $this->registerJsFile('/web/js/drivers_distance.js', ['position' => $this::POS_END, 'depends' => '\yii\web\JqueryAsset']) ?>

<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Driver */
/* @var $form yii\widgets\ActiveForm */
$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
?>
    <div class="card">
        <div class="card-body">
            <?= $form->field($model, 'is_active', ['labelOptions' => ['class' => 'col-md-2']])
                ->widget(\kartik\checkbox\CheckboxX::class, ['pluginOptions' => ['threeState' => false]]); ?>

            <?= $form->field($model, 'summary', ['labelOptions' => ['class' => 'col-md-2']])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'photo', ['labelOptions' => ['class' => 'col-md-2']])
                ->widget(\kartik\widgets\FileInput::class, [
                    'pluginOptions' => [
                        'previewFileType' => 'any',
                        'allowedFileTypes' => 'image',
                        'initialPreview' => $model->isNewRecord ? [] : ['/web/uploads/' . $model->photo],
                        'initialPreviewAsData' => true,
                        'initialPreviewShowDelete' => false,
                        'deleteUrl' => false,
                    ]
                ]) ?>

            <?= $form->field($model, 'birthday', ['labelOptions' => ['class' => 'col-md-2']])
                ->widget(\kartik\widgets\DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'convertFormat' => true,
                    ]
                ]) ?>

            <?= $form->field($model, 'buses', ['labelOptions' => ['class' => 'col-md-2']])
                ->widget(\kartik\widgets\Select2::class, [
                    'data' => \app\models\Bus::getList(),
                    'options' => [
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>

            <div class="card-footer text-right">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Cancel'), '/driver', ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
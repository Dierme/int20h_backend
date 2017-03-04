<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\models\Words */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="words-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lang_id')->widget(Select2::className(), [
            'data' => $languages,
            'options' => ['placeholder' => $model->getAttributeLabel('lang_id')],
            'pluginOptions' => [
                'allowClear' => true
            ],
    ]);
    ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

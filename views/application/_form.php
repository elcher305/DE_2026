<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Application $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="application-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'car_number')->textInput(['maxlength' => true])->label('Госномер автомобиля') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label('Описание нарушения') ?>


    <div class="form-group">
        <?= Html::submitButton('Отправить заявление', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Вишлист';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wishlist">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Твой вишлист:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'wishlist-form',
        'layout' => 'horizontal',
        'enableClientScript' => false,
    ]); ?>

        <?= $form->field($model, 'wishlist')->textarea(['autofocus' => true, 'rows' => 20]) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>


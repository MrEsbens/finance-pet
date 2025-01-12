<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reg">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Введите данные для регистрации:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <div>
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div>
                Уже есть аккаунт? <a href="./?r=login">Войти</a>
            </div>
        </div>
    </div>
</div>
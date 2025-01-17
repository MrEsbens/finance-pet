<?php
/** @var yii\web\View */
/** @var yii\bootstrap5\ActiveForm */
/** @var app\models\LoginForm */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title)?></h1>
    <p>Заполните поля чтобы войти:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin();?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])?>
            <?= $form->field($model, 'password')->passwordInput()?>
            <div class="form-group">
                <div>
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary'])?>
                </div>
            </div>
            <?php ActiveForm::end();?>
            <div>
                Нет аккаунта? <a href="./?r=registration">Зарегистрироваться</a>
            </div>
        </div>
    </div>
</div>

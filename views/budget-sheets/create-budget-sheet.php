<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\CreateBudgetSheet $budget_sheet */
/** @var string $action */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

if($action === 'create') {
    $this->title = 'Создать бюджетный лист';
}else if ($action === 'update'){
    $this->title = 'Переименовать бюджетный лист';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($budget_sheet, 'name')->textInput(['autofocus' => true]) ?>

            <?= Html::activeHiddenInput($budget_sheet, 'id', ['value' => Yii::$app->request->get('id')])?>

            <div class="form-group">
                <div>
                    <?php
                    if ($action === 'create') {
                        echo Html::submitButton('Создать лист', ['class' => 'btn btn-primary']);
                    }else if ($action === 'update'){
                        echo Html::submitButton('Переименовать лист', ['class' => 'btn btn-primary']);
                    }
                    ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

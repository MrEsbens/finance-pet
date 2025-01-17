<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Настройки пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="settings-index">
    <h1><?= Html::encode($this->title)?></h1>

    <div class="user-settings-form">
        <?php $form = ActiveForm::begin();?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true])?>

        <?= $form->field($model, 'email')->input('email')?>

        <?= $form->field($model, 'change_password')->checkbox(['id' => 'change-password-checkbox'])?>

        <div id="password-fields" style="display: none;">
            <?= $form->field($model, 'old_password')->passwordInput()?>
            <?= $form->field($model, 'password')->passwordInput()?>
            <?= $form->field($model, 'password_repeat')->passwordInput()?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>

<?php
$script = <<< JS
    $('#change-password-checkbox').change(function() {
        if ($(this).is(':checked')) {
            $('#password-fields').show();
        } else {
            $('#password-fields').hide();
            $('#usersettingsform-old_password').val('');
            $('#usersettingsform-password').val('');
            $('#usersettingsform-password_repeat').val('');
        }
    });
JS;
$this->registerJs($script);
?>

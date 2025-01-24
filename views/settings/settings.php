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

        <div id="password-fields">
            <?= $form->field($model, 'old_password')->passwordInput(['id' => 'usersettingsform-old_password'])?>
            <?= $form->field($model, 'password')->passwordInput(['id' => 'usersettingsform-password'])?>
            <?= $form->field($model, 'password_repeat')->passwordInput(['id' => 'usersettingsform-password_repeat'])?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    if ($('#change-password-checkbox').is(':checked')) {
            $('#usersettingsform-old_password').prop('disabled', false);
            $('#usersettingsform-password').prop('disabled', false);
            $('#usersettingsform-password_repeat').prop('disabled', false);
        } else {
            $('#usersettingsform-old_password').prop('disabled', true).val('');
            $('#usersettingsform-password').prop('disabled', true).val('');
            $('#usersettingsform-password_repeat').prop('disabled', true).val('');
        }
    $('#change-password-checkbox').change(function() {
        if ($(this).is(':checked')) {
            $('#usersettingsform-old_password').prop('disabled', false);
            $('#usersettingsform-password').prop('disabled', false);
            $('#usersettingsform-password_repeat').prop('disabled', false);
        } else {
            $('#usersettingsform-old_password').prop('disabled', true).val('');
            $('#usersettingsform-password').prop('disabled', true).val('');
            $('#usersettingsform-password_repeat').prop('disabled', true).val('');
        }
    });
});
JS;
$this->registerJs($script);
?>

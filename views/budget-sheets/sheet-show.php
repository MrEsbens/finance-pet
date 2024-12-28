<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $budget_sheet */
/** @var $currentMonth */
/** @var $currentYear */
/** @var $daysInMonth */
/** @var $expenses */
/** @var $incomes */

$this->title = $budget_sheet->name;
$this->params['breadcrumbs'][] = $this->title;

$prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
$prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;

$nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
$nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;

// Массив с названиями месяцев на русском языке
$monthsRu = [
    '01' => 'Январь',
    '02' => 'Февраль',
    '03' => 'Март',
    '04' => 'Апрель',
    '05' => 'Май',
    '06' => 'Июнь',
    '07' => 'Июль',
    '08' => 'Август',
    '09' => 'Сентябрь',
    '10' => 'Октябрь',
    '11' => 'Ноябрь',
    '12' => 'Декабрь',
];

// Получаем количество дней в текущем месяце
?>

<div class="container mt-5">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="d-flex justify-content-start mb-4">
        <?= Html::a('Категории', ['categories/show'], ['class' => 'btn btn-outline-info me-2']) ?>
    </div>

    <div class="d-flex justify-content-between mb-4">
        <?= Html::a('Предыдущий месяц', ['id' => $budget_sheet->id, 'budget-sheets/show', 'month' => sprintf('%02d', $prevMonth), 'year' => $prevYear], ['class' => 'btn btn-secondary']) ?>
        <h2 class="text-center"><?= Html::encode($monthsRu[sprintf('%02d', $currentMonth)]) . " " . $currentYear ?></h2>
        <?= Html::a('Следующий месяц', ['id' => $budget_sheet->id, 'budget-sheets/show', 'month' => sprintf('%02d', $nextMonth), 'year' => $nextYear], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="calendar">
        <div class="row">
            <?php
            // Инициализация переменных для подсчета общих доходов и расходов
            $totalIncome = 0;
            $totalExpense = 0;

            // Отображаем дни текущего месяца
            for ($day = 1; $day <= $daysInMonth; $day++):
                // Суммируем доходы и расходы
                if (isset($expenses[$day])) {
                    $totalExpense += (float)$expenses[$day];
                }
                if (isset($incomes[$day])) {
                    $totalIncome += (float)$incomes[$day];
                }
                ?>
                <div class="col-md-2 text-center border p-3">
                    <?= Html::a($day, Url::to(['transaction/show',
                        'day' => $day,
                        'month' => $currentMonth,
                        'year' => $currentYear,
                        'sheet_id' => $budget_sheet->id]),
                        ['class' => 'h5']) ?>
                    <p>Расходы: <?= Html::encode(isset($expenses[$day]) ? number_format($expenses[$day], 2, ',', ' ') : 0) ?> руб.</p>
                    <p>Доходы: <?= Html::encode(isset($incomes[$day]) ? number_format($incomes[$day], 2, ',', ' ') : 0) ?> руб.</p>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Вывод итоговых сумм -->
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <h4>Итоговые суммы за месяц</h4>
            <p><strong>Общий доход:</strong> <?= number_format($totalIncome, 2, ',', ' ') ?> руб.</p>
            <p><strong>Общий расход:</strong> <?= number_format($totalExpense, 2, ',', ' ') ?> руб.</p>
            <p><strong>Итоговый баланс:</strong> <?= number_format($totalIncome - $totalExpense, 2, ',', ' ') ?> руб.</p>
        </div>
    </div>
</div>

<style>
    .calendar {
        margin-top: 20px;
    }
    .border {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
</style>

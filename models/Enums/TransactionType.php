<?php
namespace app\models\enums;

enum TransactionType: string{
    case Income = 'income';
    case Expense = 'expense';
}
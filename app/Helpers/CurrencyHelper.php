<?php

function formatCurrency($value)
{
    $currencyPosition = config('config.currencyPosition');
    $currencySymbol = config('config.currencySymbol');
    if ($currencyPosition == 'left') {
        return $currencySymbol . number_format($value, 2, '.', ',');
    }else{
        return number_format($value, 2, '.', ',') .  $currencySymbol;
    }
}
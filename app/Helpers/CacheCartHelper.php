<?php

function addProductToCacheCart()
{
    $expiresAt = now()->addMinutes(10);
    Cache::put('apples', 'oranges', $expiresAt);
    $apples = Cache::get('apples');
    dd($apples);
}
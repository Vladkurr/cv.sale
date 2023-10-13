<?php
use Bitrix\Main\Loader;

if (!Loader::includeModule("iblock") || !Loader::includeModule("sale")) return false;
Bitrix\Main\Loader::registerAutoloadClasses(
    "cv.sale",
    array(
        "LightSale" => "lib/sale.php",
    )
);
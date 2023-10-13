<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

use Bitrix\Main\Localization\Loc;

if (!CModule::IncludeModule("sale")) return;
use Bitrix\Sale;


$arComponentParameters = [
    "PARAMETERS" => [
        "ORDER_LINK" => [
            "NAME" => Loc::getMessage('ORDER_LINK'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "COLS" => 25
        ],
    ]
];

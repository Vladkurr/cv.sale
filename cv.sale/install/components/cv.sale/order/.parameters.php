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

// get paysystems
$paySystemResult = Sale\PaySystem\Manager::getList(array('filter' => array('ACTIVE' => 'Y',)));
while ($paySystem = $paySystemResult->fetch()) {
    $dbRestriction = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList(array('select' => array('PARAMS'), 'filter' => array('SERVICE_ID' => $paySystem['ID'],)))->fetchall();
    $paySystem["PERSON_TYPE_ID"] = [];
    if ($dbRestriction[0]["PARAMS"]["PERSON_TYPE_ID"]) {
        $paySystem["PERSON_TYPE_ID"] = $dbRestriction[0]["PARAMS"]["PERSON_TYPE_ID"];
    } else $paySystem["PERSON_TYPE_ID"] = [];

    foreach ($paySystem["PERSON_TYPE_ID"] as $personType){
        $paySystem["NAME"] .= " [" . $personType . "]";
    }
    $arPaySystems[$paySystem["ID"]] = $paySystem["NAME"];
}

//get deliveries
$deliveries = Sale\Delivery\Services\Manager::getActiveList();
foreach ($deliveries as $key => $delivery) {
    $restrict = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList(array('select' => array('PARAMS'), 'filter' => array('SERVICE_ID' => $delivery['ID'], "SERVICE_TYPE" => \Bitrix\Sale\Services\Base\RestrictionManager::SERVICE_TYPE_SHIPMENT)))->fetchall();
    if ($restrict[0]["PARAMS"]["PERSON_TYPE_ID"]) {
        $deliveries[$key]["PERSON_TYPE_ID"] = $restrict[0]["PARAMS"]["PERSON_TYPE_ID"];
    } else $deliveries[$key]["PERSON_TYPE_ID"] = [];
}
foreach ($deliveries as $key => $delivery) {
    foreach ($delivery["PERSON_TYPE_ID"] as $person){
        $delivery["NAME"] .= " [" . $person . "]";
    }
    $arDeliveries[$delivery["ID"]] = $delivery["NAME"];
}
//get persontypes
$types = CSalePersonType::GetList();
while ($ob = $types->GetNext()) {
    $arPersons[$ob["ID"]] = $ob["NAME"] . " [" . $ob["ID"] . "]";
}

$arComponentParameters = [
    "PARAMETERS" => [
        "PAYSYSTEMS" => [
            "NAME" => Loc::getMessage('PAYSYSTEMS'),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arPaySystems,
        ],
        "DELIVERIES" => array(
            "NAME" => Loc::getMessage('DELIVERIES'),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arDeliveries,
            "ADDITIONAL_VALUES" => "N",
        ),
        "PERSONS" => array(
            "NAME" => Loc::getMessage('PERSONS'),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arPersons,
            "ADDITIONAL_VALUES" => "N",
        ),
        "ORDER" => array(
            "NAME" => Loc::getMessage('ORDER'),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => array(
                "PAYSYSTEM" => Loc::getMessage('PAYMENT'),
                "DELIVERY" => Loc::getMessage('DELIVERY')
            ),
        ),
    ]
];

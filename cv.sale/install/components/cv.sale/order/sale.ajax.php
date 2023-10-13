<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use Bitrix\Sale;

CBitrixComponent::includeComponentClass("cv.sale:order");
if (!Loader::includeModule('sale')) return;
$sale = new LightSaleOrder();
$_POST["PAYSYSTEMS"] ? $sale->arParams["PAYSYSTEMS"] = explode(",", $_POST["PAYSYSTEMS"]) : $sale->arParams["PAYSYSTEMS"] = null;
$_POST["DELIVERIES"] ? $sale->arParams["DELIVERIES"] = explode(",", $_POST["DELIVERIES"]) : $sale->arParams["DELIVERIES"] = null;

if ($_POST["AJAX"] == "FORM") {
    $arResult["FIELDS"] = $sale->GetFields($_POST["PAYERID"]);
    if ($_POST["ORDER"] == "DELIVERY") {
        $arResult["ORDER"]["FIRST"] = $sale->GetDeliveries($_POST["PAYERID"]);
        $arResult["ORDER"]["SECOND"] = $sale->GetPaySystems($_POST["PAYERID"]);
    }
    else{
        $arResult["ORDER"]["FIRST"] = $sale->GetPaySystems($_POST["PAYERID"]);
        $arResult["ORDER"]["SECOND"] = $sale->GetDeliveries($_POST["PAYERID"]);
    }
}
elseif ($_POST["AJAX"] == "ORDER"){
    if ($_POST["ORDER"] == "DELIVERY") {
        $_POST["PAY_OR_DEL"] ? $ids  = explode(",", $_POST["PAY_OR_DEL"]) : $ids = null;
        $arResult["ORDER"]["SECOND"] = $sale->GetPaySystems(null, $ids);
    }
    else{
        $_POST["PAY_OR_DEL"] ? $ids  = explode(",", $_POST["PAY_OR_DEL"]) : $ids = null;
        $arResult["ORDER"]["SECOND"] = $sale->GetDeliveries(null, $ids);
    }
}
?>


<?php if ($_POST["AJAX"] == "FORM"): // подгрузка формы целиком?>
    <form class="form_order" action="" id="form_<?= $_POST["PAYERID"] ?>">
        <h2>свойства</h2>
        <div class="props">
            <?php foreach ($arResult["FIELDS"] as $arField): ?>
                <label for="field_<?= $arField["ID"] ?>"><?= $arField["NAME"] ?></label>
                <input type="text" id="field_<?= $arField["ID"] ?>"
                       placeholder="<?= $arField["NAME"] ?>">
            <?php endforeach; ?>
        </div>

        <h2><?= $arResult["ORDER"]["FIRST"][0]["HAVE_PRICE"] ? "Оплата" : "Доставка" ?></h2>
        <div class="first-order-ajax">
            <?php $c = 0; ?>
            <?php foreach ($arResult["ORDER"]["FIRST"] as $arOrder): ?>
                <?php $arOrder["HAVE_PRICE"] ? $idType = "PAYMENTID" : $idType = "DELIVERYID"; ?>
                <label for="<?= $idType ?>_<?= $arOrder["CODE"] ?>"><?= $arOrder["NAME"] ?></label>
                <?php if ($c == 1): ?>
                    <input class="ajax-btn"
                           id="<?= $idType ?>_<?= $arOrder["ID"] ?>"
                           data-ajax="<?= $arOrder["HAVE_PRICE"] ? implode(",", $arOrder["DELIVERY"]) : implode(",", $arOrder["PAY_SYSTEMS"]); ?>"
                           type="radio" checked/>
                <?php else: ?>
                    <input class="ajax-btn"
                           id="<?= $idType ?>_<?= $arOrder["ID"] ?>"
                           data-ajax="<?= $arOrder["HAVE_PRICE"] ? implode(",", $arOrder["DELIVERY"]) : implode(",", $arOrder["PAY_SYSTEMS"]); ?>"
                           type="radio"/>
                <?php endif; ?>
                <?php $c++ ?>
            <?php endforeach; ?>
        </div>
        <h2><?= $arResult["ORDER"]["SECOND"][0]["HAVE_PRICE"] ? "Оплата" : "Доставка" ?></h2>
        <div class="second-order-ajax">
            <?php $c = 0; ?>
            <?php foreach ($arResult["ORDER"]["SECOND"] as $arOrder): ?>
                <?php $arOrder["HAVE_PRICE"] ? $idType = "PAYMENTID" : $idType = "DELIVERYID"; ?>
                <label for="<?= $idType ?>_<?= $arOrder["CODE"] ?>"><?= $arOrder["NAME"] ?></label>
                <?php if ($c == 0): ?>
                    <input id="<?= $idType ?>_<?= $arOrder["ID"] ?>" type="radio" checked/>
                <?php else: ?>
                    <input id="<?= $idType ?>_<?= $arOrder["ID"] ?>" type="radio"/>
                <?php endif; ?>
                <?php $c++ ?>
            <?php endforeach; ?>
        </div>
        <button class="submit_custom_order">Отправить</button>
    </form>
<?php endif; ?>


<?php if ($_POST["AJAX"] == "ORDER"): // подгрузка оплат / доставок?>
    <div class="second-order-ajax">
        <?php $c = 0; ?>
        <?php foreach ($arResult["ORDER"]["SECOND"] as $arOrder): ?>
            <?php $arOrder["HAVE_PRICE"] ? $idType = "PAYMENTID" : $idType = "DELIVERYID"; ?>
            <?php if ($c == 0): ?>
                <input class="active" id="<?= $idType ?>_<?= $arOrder["ID"] ?>" type="radio" checked/>
            <?php else: ?>
                <input class="active" id="<?= $idType ?>_<?= $arOrder["ID"] ?>" type="radio"/>
            <?php endif; ?>
            <label for="<?= $arOrder["CODE"] ?>"><?= $arOrder["NAME"] ?></label>
            <?php $c++ ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

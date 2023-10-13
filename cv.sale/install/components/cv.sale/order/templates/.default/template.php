<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
// выбор первого выводимого плательщика
$firstPayer = $arResult["PERSONTYPE"][0]["ID"]
?>
<div class="tabs-container">
    <?php foreach ($arResult["PERSONTYPE"] as $person): ?>
        <button id="tab_<?= $person["ID"] ?>" class="tab-button"><?= $person["NAME"] ?></button>
    <?php endforeach; ?>
</div>
<div class="persons-container">
    <form class="form_order" action="" id="form_<?= $firstPayer ?>">
        <h2 id="person_<?= $firstPayer ?>" class="person"></h2>
        <h2>свойства</h2>
        <div class="props">
            <?php foreach ($arResult["FIELDS"] as $arField): ?>
                <?php if ($firstPayer == $arField["PERSON_TYPE_ID"]): ?>
                    <label for="field_<?= $arField["ID"] ?>"><?= $arField["NAME"] ?></label>
                    <input class="active" type="text" id="field_<?= $arField["ID"] ?>"
                           placeholder="<?= $arField["NAME"] ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <h2><?= $arResult["ORDER"]["FIRST"][0]["HAVE_PRICE"] ? "Оплата" : "Доставка" ?></h2>
        <div class="first-order-ajax">
            <?php $c = 0; ?>
            <?php foreach ($arResult["ORDER"]["FIRST"] as $arOrder): ?>
                <?php if (!$arOrder["PERSON_TYPE_ID"] || in_array($firstPayer, $arOrder["PERSON_TYPE_ID"])): ?>
                    <?php $arOrder["HAVE_PRICE"] ? $idType = "PAYMENTID" : $idType = "DELIVERYID"; ?>
                    <label for="<?= $arOrder["CODE"] ?>"><?= $arOrder["NAME"] ?></label>
                    <?php if ($c == 1): ?>
                        <input class="ajax-btn"
                               id="<?= $idType ?>_<?= $arOrder["ID"] ?>"
                               name="first"
                               data-ajax="<?= $arOrder["HAVE_PRICE"] ? implode(",", $arOrder["DELIVERY"]) : implode(",", $arOrder["PAY_SYSTEMS"]); ?>"
                               type="radio" checked/>
                    <?php else: ?>
                        <input class="ajax-btn"
                               id="<?= $idType ?>_<?= $arOrder["ID"] ?>"
                               name="first"
                               data-ajax="<?= $arOrder["HAVE_PRICE"] ? implode(",", $arOrder["DELIVERY"]) : implode(",", $arOrder["PAY_SYSTEMS"]); ?>"
                               type="radio"/>
                    <?php endif; ?>
                <?php endif; ?>
                <?php $c++ ?>
            <?php endforeach; ?>
        </div>
        <h2><?= $arResult["ORDER"]["SECOND"][0]["HAVE_PRICE"] ? "Оплата" : "Доставка" ?></h2>
        <div class="second-order-ajax">
            <?php $c = 0; ?>
            <?php foreach ($arResult["ORDER"]["SECOND"] as $arOrder): ?>
                <?php if (!$arOrder["PERSON_TYPE_ID"] || in_array($firstPayer, $arOrder["PERSON_TYPE_ID"])): ?>
                    <?php $arOrder["HAVE_PRICE"] ? $idType = "PAYMENTID" : $idType = "DELIVERYID"; ?>
                    <label for="<?= $arOrder["CODE"] ?>"><?= $arOrder["NAME"] ?></label>
                    <?php if ($c == 0): ?>
                        <input id="<?= $idType ?>_<?= $arOrder["ID"] ?>"
                               name="second"
                               type="radio" checked/>
                    <?php else: ?>
                        <input id="<?= $idType ?>_<?= $arOrder["ID"] ?>"
                               name="second"
                               type="radio"/>
                    <?php endif; ?>
                <?php endif; ?>
                <?php $c++ ?>
            <?php endforeach; ?>
        </div>
        <button class="submit_custom_order">Отправить</button>
    </form>
</div>
<?php include('script.php'); ?>

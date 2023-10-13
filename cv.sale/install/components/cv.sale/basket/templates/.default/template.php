<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 * @var $USER
 */
//Add2BasketByProductID(149, 2);
?>
<div class="coupon">
    <input id="coupon-field" type="text">
    <button id="coupon-submit">Применить</button>
</div>

<div class="basket-items">
    <?php foreach ($arResult["ITEMS"] as $arItem):?>
    <div class="basket-item">
        <div class="basket-item-name"><?= $arItem["NAME"] ?></div>
        <div class="basket-item-price"><?= $arItem["BASE_PRICE"] ?></div>
        <div class="basket-item-discount-price"><?= $arItem["PRICE"] ?></div>
        <div class="basket-item-quantity"><?= $arItem["QUANTITY"] ?></div>
    </div>
    <?php endforeach; ?>
</div>
    <a href="<?= $arParams["ORDER_LINK"] ?>" class="offer">Оформить заказ</a>
<?php include('script.php'); ?>
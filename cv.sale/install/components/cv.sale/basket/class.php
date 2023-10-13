<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use Bitrix\Sale\DiscountCouponsManager;

Loader::includeModule('cv.sale');

class LightSaleBasket extends LightSale
{
    // формирование $arRresult
    public function SetArResult()
    {
        $this->arResult["ITEMS"] = $this->BasketitemsBeutify();
    }

    //функция инициализации компонента. при получении купона обновляет цены в корзине.
    public function executeComponent()
    {
        $this->_checkModules();
        if ($_REQUEST["AJAX"]) {
            $coupon = $_REQUEST["AJAX"];
            DiscountCouponsManager::add($coupon);
        }
        $this->SetArResult();
        $this->includeComponentTemplate();
    }
}

<?php
use \Bitrix\Main\Loader;

class LightSale extends CBitrixComponent
{
    // проверка подключения модуля sale
    public function _checkModules()
    {
        if (!Loader::includeModule('sale') || !Loader::includeModule('iblock')) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }
        return true;
    }

    // получение обновленного списка цен для элементов корзины
    public function GetPrices($basketItems)
    {
        $discounts = \Bitrix\Sale\Discount::buildFromBasket($basketItems, new \Bitrix\Sale\Discount\Context\Fuser($basketItems->getFUserId(true)));
        if ($_REQUEST["AJAX"]) $basketItems->refreshData(['PRICE', 'COUPONS']);
        if (!$discounts) return;
        $discounts->calculate();
        $result = $discounts->getApplyResult(true);
        $prices = $result['PRICES']['BASKET'];
        return $prices;
    }

    // формирование массива товаров со всем необходимым
    public function BasketitemsBeutify()
    {
        $basketItems = $this->GetBasketItems();
        $basketArray = $basketItems->toArray();

        // получние цен
        $prices = $this->GetPrices($basketItems);

        // формирование массива id всех товаров и добавление цен в общий массив
        $ids = [];
        foreach ($basketArray as &$item) {
            $ids[] = $item["PRODUCT_ID"];
            $item["BASE_PRICE"] = $prices[$item["ID"]]["BASE_PRICE"];
            $item["PRICE"] = $prices[$item["ID"]]["PRICE"];
            $item["DISCOUNT"] = $prices[$item["ID"]]["DISCOUNT"];
        }

        //  добавление в общий массив всех полей и свойств товаров
        $DbItems = CIBlockElement::GetList([], ["ID" => $ids], false, false);
        foreach ($basketArray as &$item) {
            if (!$DbItem = $DbItems->GetNextElement()) break;
            $item["PROPERTIES"] = $DbItem->GetProperties();
            $item["FIELDS"] = $DbItem->GetFields();
        }
        return $basketArray;
    }

    // получение прееменной пользователя. Если он не авторизован, то переменная пользователя Unknown. Может вернуть NULL
    public function GetUser()
    {
        global $USER;
        $sort = ['sort' => 'asc'];
        $order = 'sort';
        $unauthorizedUser = CUser::GetList($sort, $order, ["LOGIN" => "Unknown"])->fetch();
        if ($USER->IsAuthorized()) return $USER;
        return $unauthorizedUser;
    }

    // Получить id текущего пользователя, или общего пользователя Unknown
    public function GetUserId()
    {
        $USER = $this->GetUser();
        if ($USER instanceof CUser) {
            return $USER->GetID();
        }
        if (!$USER) {
            $user = new CUser;
            $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
            $size = strlen($chars) - 1;
            $password = '';
            while ($length--) {
                $password .= $chars[random_int(0, $size)];
            }
            $arFields = array(
                "NAME" => "Неавторизованный пользователь",
                "LOGIN" => "Unknown",
                "EMAIL" => "Unknown@Unknown.ru",
                "LID" => "ru",
                "ACTIVE" => "Y",
                "PASSWORD" => $password,
                "CONFIRM_PASSWORD" => $password,
            );
            return $user->Add($arFields);
        } else {
            return $USER["ID"];
        }
    }

    // получение корзины для конкретного пользователя
    public function GetBasketItems($userId = null)
    {
        if ($userId == null) $userId = $this->GetUserId();
        $arFUser = CSaleUser::GetList(['USER_ID' => $userId]);
        $basketItems = Bitrix\Sale\Basket::loadItemsForFUser($arFUser['ID'], SITE_ID);
        return $basketItems;
    }
}
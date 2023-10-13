<?php
/**
 * @var $arParams
 */
CJSCore::Init(array("jquery"));
?>
<script>
    // формирование массива заказа
    document.querySelector(".persons-container").addEventListener("click", makeOrder)

    async function makeOrder(event) {
        event.preventDefault();
        let target = event.target.closest(".submit_custom_order");
        if (!target) return;

        inputs = document.querySelectorAll(".form_order input");
        let data = new URLSearchParams()
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].checked) data.set(inputs[i].id.split(/_/)[0], inputs[i].id.split(/_/)[1]);
            if (inputs[i].placeholder && inputs[i].value) data.set(inputs[i].id.replace(/[^0-9]/g, ""), inputs[i].value);
        }
        data.set("PERSONTYPEID", document.querySelector(".form_order").id.replace(/[^0-9]/g, ""))
        let curDir = document.location.protocol + '//' + document.location.host + document.location.pathname;
        // activate
        data.set("MAKE_ORDER", "Y");
        //activate
        await fetch(curDir, {
            method: 'POST',
            body: data,
        })
    }

    // переключение между плательщиками
    document.querySelector(".tabs-container").addEventListener('click', changePayer);

    async function changePayer(event) {
        const target = event.target.closest('.tab-button');
        if (!target || event.target.classList.contains("active")) return;

        let payerid = event.target.id.replace(/[^0-9]/g, "");
        text = document.querySelector('#title-search-input').value;
        $.ajax({
            url: `/bitrix/components/cv.sale/order/sale.ajax.php`,
            method: 'post',
            dataType: 'html',
            data: {
                PAYERID: payerid,
                AJAX: "FORM",
                ORDER: "<?= $arParams["ORDER"] ?>",
                PAYSYSTEMS: "<?= implode(",", $arParams["PAYSYSTEMS"]) ?>",
                DELIVERIES: "<?= implode(",", $arParams["DELIVERIES"]) ?>",
            },
            success: function (data) {
                tabs = document.querySelectorAll(".tab-button");
                for (let i = 0; i < tabs.length; i++) {
                    if (tabs[i].classList.contains("active")) tabs[i].classList.remove("active");
                }
                target.classList.add("active");
                $('.form_order').remove();
                $('.persons-container').append(data);

            }
        });
    }

    //Подзгрузка оплат/доставок в зависимости от ограничений по оплатам/доставкам у оплат/доставок
    document.addEventListener('click', loadSecondOrder);

    async function loadSecondOrder(event) {
        const target = event.target.closest('.ajax-btn');
        if (!target || event.target.classList.contains("active")) return;

        let ajaxid = target.getAttribute("data-ajax");
        $.ajax({
            url: `/bitrix/components/cv.sale/order/sale.ajax.php`,
            method: 'post',
            dataType: 'html',
            data: {
                PAY_OR_DEL: ajaxid,
                AJAX: "ORDER",
                ORDER: "<?= $arParams["ORDER"] ?>",
                PAYSYSTEMS: "<?= implode(",", $arParams["PAYSYSTEMS"]) ?>",
                DELIVERIES: "<?= implode(",", $arParams["DELIVERIES"]) ?>",
            },
            success: function (data) {
                tabs = document.querySelectorAll(".ajax-btn");
                for (let i = 0; i < tabs.length; i++) {
                    if (tabs[i].classList.contains("active")) tabs[i].classList.remove("active");
                }
                target.classList.add("active");
                $('.second-order-ajax').html(data);
            }
        });
    }
</script>

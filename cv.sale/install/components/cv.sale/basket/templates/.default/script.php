<?php
/**
 * @var $arParams
 */
CJSCore::Init(array("jquery"));
?>
<script>
    document.querySelector("#coupon-submit").addEventListener("click", ()=>{
        let coupon = document.querySelector("#coupon-field").value
        window.location.replace(`?AJAX=${coupon}`);

        //$.ajax({
        //    url: `/local/components/sale/basket/basket.ajax.php`,
        //    method: 'post',
        //    dataType: 'html',
        //    data: {
        //        PAY_OR_DEL: ajaxid,
        //        AJAX: "ORDER",
        //        ORDER: "<?php //= $arParams["ORDER"] ?>//",
        //        PAYSYSTEMS: "<?php //= implode(",", $arParams["PAYSYSTEMS"]) ?>//",
        //        DELIVERIES: "<?php //= implode(",", $arParams["DELIVERIES"]) ?>//",
        //    },
        //    success: function (data) {
        //        tabs = document.querySelectorAll(".ajax-btn");
        //        for (let i = 0; i < tabs.length; i++) {
        //            if (tabs[i].classList.contains("active")) tabs[i].classList.remove("active");
        //        }
        //        target.classList.add("active");
        //        $('.second-order-ajax').html(data);
        //    }
        //});
    })



</script>

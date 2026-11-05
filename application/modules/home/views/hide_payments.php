<style>
    .deposit_type, .deposit_type1, .cardPayment, .cardPayment1, .payment_status, .payment-cards, [data-payhide],
    *:has(> #chart_div_superadmin), *:has(> #piechart_3d_superadmin), .card:has(#topServicesChart) { display: none !important; }
</style>
<script>
    // Payments are switched off ($config['payments_enabled']). The fields stay in the
    // form so saving still works; they are only hidden and no longer required.
    document.addEventListener('DOMContentLoaded', function () {
        var names = ['visit_charges', 'discount', 'grand_total', 'deposited_amount', 'deposit_type', 'pay_now_appointment', 'card_type', 'cardholder', 'card_number', 'expire_date', 'cvv', 'cvv_number'];
        names.forEach(function (name) {
            document.querySelectorAll('[name="' + name + '"]').forEach(function (field) {
                field.required = false;
                var box = field.parentElement.closest('.form-group, .d-flex, [class*="col-"]');
                if (box) { box.setAttribute('data-payhide', ''); }
            });
        });
    });
</script>

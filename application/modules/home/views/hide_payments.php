<style>
    .cardPayment, .cardPayment1, .payment-cards,
    *:has(> #chart_div_superadmin), *:has(> #piechart_3d_superadmin), .card:has(#topServicesChart) { display: none !important; }
</style>
<script>
    // Online card payment is off. Cash payments, invoices and expenses stay available.
    document.addEventListener('DOMContentLoaded', function () {
        var names = ['card_type', 'cardholder', 'card_number', 'expire_date', 'cvv', 'cvv_number'];
        names.forEach(function (name) {
            document.querySelectorAll('[name="' + name + '"]').forEach(function (field) {
                field.required = false;
                var box = field.parentElement.closest('.form-group, .d-flex, [class*="col-"]');
                if (box) { box.style.display = 'none'; }
            });
        });
        document.querySelectorAll('option[value="Card"], option[value="card"]').forEach(function (option) { option.remove(); });
    });
</script>

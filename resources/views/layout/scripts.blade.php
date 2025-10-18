<script>
var invoiceTitle = "{{ __('messages.invoice.copy_invoice_url') }}";
    function copyURL(url) {
        const el = document.createElement('textarea');
        el.value = url;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        new FilamentNotification()
            .title(invoiceTitle)
            .icon('heroicon-o-clipboard-document-check')
            .iconColor('success')
            .send();
    }


    function razorPay(key, name, currency, amount, invoiceId, premail, prename) {
        let options = {
            key: key,
            amount: amount * 100,
            currency: currency,
            name: name,
            order_id: '',
            description: '',
            notes: {
                invoiceId: invoiceId
            },
            image: "{{ getLogoUrl() }}",
            callback_url: "{{ route('razorpay.success') }}",
            prefill: {
                email: premail,
                name: prename,
                invoiceId: invoiceId
            },
            readonly: {
                name: true,
                email: true
            },
            modal: {
                ondismiss: function() {
                    window.location.href = "{{ route('razorpay.failed') }}";
                }
            }
        };

        let razorPay = new Razorpay(options);
        razorPay.open();
        razorPay.on("payment.failed", function() {
            window.location.href = "{{ route('razorpay.failed') }}";
        });
    }

    //redirect to whatsapp
    document.addEventListener('open-whatsapp-link', function(event) {
        const url = event.detail[0];
        window.open(url, '_blank');
    });

    // MercadoPago redirect
    function openMercadoPago(publicKey, preferenceId) {
        const mp = new MercadoPago(publicKey, {
            locale: 'en-US'
        });

        mp.checkout({
            preference: {
                id: preferenceId
            },
            autoOpen: true
        });
    }
</script>

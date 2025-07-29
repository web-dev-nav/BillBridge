<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice | {{ getAppName() }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset(getSettingValue('favicon_icon')) }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!-- General CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins.css') }}">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js" data-turbolinks-eval="false" data-turbo-eval="false">
    </script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('messages.js') }}"></script>
    @vite('resources/js/payment.js')
  
    <script >
        let sweetAlertIcon = "{{ asset('images/remove.png') }}";
        let decimalsSeparator = "{{ getSettingValue('decimal_separator') }}";
        let thousandsSeparator = "{{ getSettingValue('thousand_separator') }}";
        let currentDateFormat = "{{ currentDateFormat() }}";
        let momentDateFormat = "{{ momentJsCurrentDateFormat() }}";
        let currency = "{{ getCurrencySymbol() }}";
        let getUserLanguages = "{{ $userLang }}";
        let currentRouteName = "{{ Route::currentRouteName() }}";
       
        Lang.setLocale(getUserLanguages)
        @if (!empty($stripeKey))
            let stripe = Stripe('{{ $stripeKey ?? config('services.stripe.key') }}');
        @endif
        let options = {
            'key': "{{ config('payments.razorpay.key') }}",
            'amount': 0, //  100 refers to 1
            'currency': 'INR',
            'name': "{{ getAppName() }}",
            'order_id': '',
            'description': '',
            'image': '{{ asset(getLogoUrl()) }}', // logo here
            'callback_url': "{{ route('razorpay.success') }}",
            'prefill': {
                'email': '', // client email here
                'name': '', // client name here
                'invoiceId': '', //invoice id
            },
            'readonly': {
                'name': 'true',
                'email': 'true',
                'invoiceId': 'true',
            },
            'theme': {
                'color': '#4FB281',
            },
            'modal': {
                'ondismiss': function() {
                    $('#paymentForm').modal('hide');
                    displayErrorMessage('Payment not completed.');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
            },
        };
    </script>

    @routes

</head>
@php $styleCss = 'style'; @endphp

<body>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                        <div class="p-12">
                            <div class="card">
                                <form id="clientPaymentForm">
                                    <div class="card-body">
                                        @session('error')
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('error') }}
                                        </div>
                                        @endsession
                                        <input type="hidden" name="invoice_id" id="client_invoice_id"
                                            value="{{ $totalPayable['id'] }}">
                                        <div class="row">
                                            <div class="form-group col-sm-6 mb-5">
                                                <label for="payable_amount"
                                                    class="form-label mb-3">{{ __('messages.payment.payable_amount') }}:</label>
                                                <div class="input-group mb-5">
                                                    <input type="text" id="payable_amount" name="payable_amount"
                                                        class="form-control"
                                                        value="{{ $totalPayable['total_amount'] }}" readonly>
                                                    <a class="input-group-text bg-secondary cursor-default text-decoration-none"
                                                        href="javascript:void(0)">
                                                        <span>{{ getInvoiceCurrencyIcon($invoice->currency_id) }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                            {{-- @dd($paymentType) --}}
                                            <div class="form-group col-sm-6 mb-5">
                                                <label for="client_payment_type"
                                                    class="form-label required mb-3">{{ __('messages.payment.payment_type') }}:</label>
                                                <select id="client_payment_type" name="payment_type" class="form-select"
                                                    required>
                                                    <option value="" disabled selected>Select Payment Type
                                                    </option>
                                                    @foreach ($paymentType as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6 mb-5 amount">
                                                <label for="amount"
                                                    class="form-label required mb-3">{{ __('messages.invoice.amount') }}:</label>
                                                <input type="number" id="amount" name="amount" class="form-control"
                                                    step="any" required
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                                <span id="error-msg" class="text-danger"></span>
                                            </div>
                                            <div class="form-group col-sm-6 mb-5">
                                                <label for="client_payment_mode"
                                                    class="form-label required mb-3">{{ __('messages.payment.payment_method') }}:</label>
                                                <select id="client_payment_mode" name="payment_mode" class="form-select"
                                                    required>
                                                    <option value="" disabled selected>Select Payment Method
                                                    </option>
                                                    @foreach ($paymentMode as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6 mb-5" id="transaction">
                                                <label for="transactionId"
                                                    class="form-label mb-3">{{ __('messages.payment.transaction_id') }}:</label>
                                                <input type="text" id="transactionId" name="transaction_id"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group col-sm-12 mb-5">
                                                <label for="payment_note"
                                                    class="form-label required mb-3">{{ __('messages.invoice.note') }}:</label>
                                                <textarea id="payment_note" name="notes" class="form-control" rows="5" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer pt-0">
                                        <button type="submit" class="btn btn-primary me-2" id="btnPay"
                                            data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing..."
                                            data-new-text="{{ __('messages.common.pay') }}">{{ __('messages.common.pay') }}</button>
                                        <a href="#"
                                            class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

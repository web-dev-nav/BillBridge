@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <h2> {{ __('messages.mail_content.dear') . ' ' . $adminName }},</h2>
        <h4 style="color: green">{{ __('messages.mail_content.payment_received_successfully_for_invoice') }}
            #{{ $invoiceNo }} ..!</h4>
        <p>{{ __('messages.mail_content.payment_date') }} : <strong>{{ $receivedDate }}</strong></p>
        <p>{{ __('messages.mail_content.received_payment_amount') }} : <strong>{{ $receivedAmount }}</strong> </p>
        <br>
        <p>{{ __('messages.mail_content.this_is_a_confirmation_that_amount_has_received') }}.</p>
        <div style="display: flex;justify-content: center">
            <a href="{{ route('invoices.show', ['invoice' => $invoiceId, 'active' => 'paymentHistory']) }}"
                style="padding: 7px 15px;text-decoration: none;font-size: 14px;background-color: green ;font-weight: 500;border: none;border-radius: 8px;color: white">
                {{ __('messages.mail_content.view_payment_history') }}
            </a>
        </div>
    </div>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent

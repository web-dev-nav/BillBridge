@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <h2>{{ __('messages.mail_content.dear') . ' ' . $clientFullName }}, <b></b></h2><br>
        <p>{{ __('messages.i_hope_you_are_well') }}.</p>>
        <p>{{ __('messages.mail_content.i_just_wanted_to_drop_you_a_quick_note_to_remind_you_that') }}
            <b>{{ numberFormat($totalDueAmount) }}</b> {{ __('messages.mail_content.in_respect_of_our_invoice') }}
            <b>{{ $invoiceNumber }}</b> {{ __('messages.mail_content.is_due_for_payment_on') }} <b>{{ $dueDate }}</b>.
        </p>
        <br>
        <div style="display: flex;justify-content: center">
            <a href="{{ route('invoice-show-url', $invoiceNumber) }}"
                style="padding: 7px 15px;text-decoration: none;font-size: 14px;background-color: green ;font-weight: 500;border: none;border-radius: 8px;color: white">
                {{ __('messages.mail_content.view_invoice') }}
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

@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <h2>{{ __('messages.mail_content.welcome') . ' ' . __('messages.mail_content.to') }} {{ $clientName }}, <b></b>
        </h2><br>
        <p> {{ __('messages.mail_content.your_account_has_been_successfully_created_on') . ' ' . getAppName() }}</p>
        <p>{{ __('messages.mail_content.your_email_address_is') }} <strong>{{ $userName }}</strong></p>
        <p>{{ __('messages.mail_content.in') }} {{ getAppName() }},
            {{ __('messages.mail_content.you_can_manage_all_of_your_invoices') }}.</p>
        <p>{{ __('messages.mail_content.thank_for_joining_and_have_a_great_day') }}!</p><br>
        <div style="display: flex;justify-content: center">
            <a href="{{ route('client.password.reset', $client_id) }}"
                style="padding: 7px 15px;text-decoration: none;font-size: 14px;background-color:  #0000FF;font-weight: 500;border: none;border-radius: 8px;color: white">
                {{ __('messages.mail_content.join_now') }}
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

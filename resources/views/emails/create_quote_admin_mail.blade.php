@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <h2>{{ __('messages.mail_content.dear') . ' ' . $clientName }}, <b></b></h2><br>
        <p>{{ __('messages.i_hope_you_are_well') }}.</p>
        <p>{{ __('messages.mail_content.please_see_attached_the_quote') }} #{{ $quoteNumber }}.
            {{ __('messages.mail_content.the_quote_is_due_by') }}
            {{ $dueDate }}.</p>
        <p>{{ __('messages.mail_content.please_dont_hesitate_to_get_in_touch') }}</p><br>
        <div style="display: flex;justify-content: center">
            <a href="{{ route('filament.admin.resources.quotes.view', $quoteId) }}"
                style="padding: 7px 15px;text-decoration: none;font-size: 14px;background-color: #df4645;font-weight: 500;border: none;border-radius: 8px;color: white">
                {{ __('messages.mail_content.view_quote') }}
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

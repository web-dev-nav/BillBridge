<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    {{-- <x-filament::input.wrapper>
        <x-filament::input type="hidden" id="default_invoice_color" wire:model="invoiceColor" />
    </x-filament::input.wrapper> --}}

    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <div class="color-picker-container" wire:ignore>
            <div class="color-picker"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css" />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const pickr = Pickr.create({
                el: '.color-picker',
                theme: 'nano',

                swatches: [
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {
                    // Main components
                    preview: true,
                    hue: true,

                    interaction: {
                        input: true,
                    }
                }
            });

            let color = "{{ $this->invoiceColor }}";
            setTimeout(function() {
                pickr.setColor(color);
            }, 200);

            pickr.on('change', (color) => {
                const rgbaColor = color.toHEXA().toString();
                const input = document.getElementById('default_invoice_color');
                input.value = rgbaColor;
                input.dispatchEvent(new Event('input', {
                    bubbles: true
                }));

                $(".bgColor").css("background-color", rgbaColor);
                $(".fontColor").css("color", rgbaColor);
                $(".borderColor").css("border-color", rgbaColor);
                pickr.setColor(rgbaColor);

            });

            document.addEventListener('updateColorPicker', function(event) {
                let newColor = event.detail.color;

                if (pickr) {
                    pickr.setColor(newColor);
                }
            });
        });
    </script>
</x-dynamic-component>
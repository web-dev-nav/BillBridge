<x-filament::link id="gotoFullScreen" title="Fullscreen" class="cursor-pointer" icon-color="primary">
    <x-heroicon-o-arrows-pointing-out class="w-6 h-6" id="fullScreenIcon" />
</x-filament::link>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var fullScreenButton = document.getElementById("gotoFullScreen");
        var fullScreenIcon = document.getElementById("fullScreenIcon");

        fullScreenButton.addEventListener("click", function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fullScreenIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25" />
                `;
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
                fullScreenIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                `;
            }
        });
    });
</script>

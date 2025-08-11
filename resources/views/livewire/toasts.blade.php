<div
        x-data="{
        show: @entangle('show'),
        progress: 100,
        timer: null,
        leaveDuration: 200,

        init() {
            this.$watch('show', value => {
                if (value) {
                    this.progress = 100;
                    this.startTimer();
                }
            });
        },

        startTimer() {
            clearTimeout(this.timer);
            this.progress = 0;
            this.timer = setTimeout(() => {
                this.startHide();
            }, {{ $duration }});
        },

        close() {
            clearTimeout(this.timer);
            this.startHide();
        },

        startHide() {
            this.show = false;
            setTimeout(() => $wire.hide(), this.leaveDuration + 20);
        }
    }"
        x-cloak
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="fixed bottom-4 right-4 w-full max-w-sm z-100"
>

<div class="relative @if($type === 'info') bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800/30
                @elseif($type === 'success') bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800/30
                @elseif($type === 'warning') bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800/30
                @elseif($type === 'error') bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800/30
                @endif border rounded-lg shadow-lg overflow-hidden backdrop-blur-sm">
        
        <!-- Progress bar -->
        <div class="absolute top-0 left-0 h-1 @if($type === 'info') bg-blue-500
                    @elseif($type === 'success') bg-green-500
                    @elseif($type === 'warning') bg-yellow-500
                    @elseif($type === 'error') bg-red-500
                    @endif transition-all ease-linear"
             x-bind:style="`width: ${progress}%`"
             x-init="setTimeout(() => progress = 100, 100)"
             style="transition-duration: {{ $duration }}ms;">
        </div>

        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($type === 'info')
                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 text-lg"></i>
                    @elseif($type === 'success')
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-lg"></i>
                    @elseif($type === 'warning')
                        <i class="fas fa-exclamation-triangle text-yellow-500 dark:text-yellow-400 text-lg"></i>
                    @elseif($type === 'error')
                        <i class="fas fa-times-circle text-red-500 dark:text-red-400 text-lg"></i>
                    @endif
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium @if($type === 'info') text-blue-800 dark:text-blue-200
                              @elseif($type === 'success') text-green-800 dark:text-green-200
                              @elseif($type === 'warning') text-yellow-800 dark:text-yellow-200
                              @elseif($type === 'error') text-red-800 dark:text-red-200
                              @endif">
                        {{ $message }}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="close()"
                        class="inline-flex text-neutral-400 hover:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 dark:text-neutral-500 dark:hover:text-neutral-400 transition-colors duration-200 p-1 rounded"
                    >
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
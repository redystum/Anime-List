<div x-data="{
        show: @entangle('show'),
        init() {
            this.$watch('show', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
     }"
     x-cloak>
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
         @wheel.prevent
         @scroll.prevent
         @touchmove.prevent
         wire:click="closeModal">
        <div class="bg-white dark:bg-neutral-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-neutral-200 dark:border-neutral-700 overflow-x-hidden"
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @wheel.stop
             @scroll.stop
             @touchmove.stop
             @click.stop>

            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Results for: {{ $query }}</h2>
                <button wire:click="closeModal"
                        class="text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>


            @if($animes)
                <div class="bg-neutral-100 dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-visible">
                    @forelse($animes as $anime)
                        <div class="group flex items-center p-4 border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors overflow-visible"
                             x-data="{ showNotePopup: false }">
                            <div class="flex items-center space-x-2 mr-4 text-lg">
                                @if($anime['alreadyOnList'])
                                    <button class="p-2 text-neutral-600 dark:text-neutral-400 transition-colors cursor-pointer">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @else
                                    <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                            wire:click="addToList({{ $anime['id'] }})">
                                        <i class="fas fa-add"></i>
                                    </button>
                                @endif
                            </div>

                            <div class="w-fit h-32 flex-shrink-0 mr-4">
                                <img
                                        src="{{ $anime['main_picture']['large'] ?? $anime['main_picture']['medium'] }}"
                                        alt="Anime Cover"
                                        class="w-full h-full object-cover rounded"
                                >
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-semibold text-lg text-neutral-800 dark:text-neutral-100">{{ $anime['title'] }}</h3>
                            </div>


                        </div>
                    @empty
                        <div class="p-6 text-center text-neutral-500 dark:text-neutral-400">
                            <h2 class="text-xl font-bold">No Results Found</h2>
                            <p class="mt-2">Try a different search term.</p>
                        </div>
                    @endforelse
                </div>
            @elseif(isset($error))
                <div class="p-6 text-center text-red-600 dark:text-red-400">
                    <h2 class="text-xl font-bold">Error</h2>
                    <p class="mt-2">{{ $error }}</p>
                </div>
            @endif

            <div class="text-sm p-6 text-center text-neutral-500 dark:text-neutral-400">
                <h2 class="font-bold">You can skip this by entering the mal id directly, see the example below:</h2>
                <code class="mt-2 bg-neutral-200 dark:bg-neutral-700 text-purple-600 p-2 rounded">
                    id:30
                </code>
            </div>
        </div>
    </div>
</div>

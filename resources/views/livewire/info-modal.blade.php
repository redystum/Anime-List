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
        <div class="bg-white dark:bg-neutral-800 rounded-lg max-w-6xl w-full max-h-[90vh] overflow-y-auto border border-neutral-200 dark:border-neutral-700 overflow-x-hidden"
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

            @if($anime)
                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ $anime->title }}</h2>
                    <div class="flex items-center gap-x-8">
                        <div class="flex items-center space-x-2 mr-4 text-lg">
                            @if($anime->completed)
                                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                        wire:click="toggleCompletedOnInfo({{ $anime->id }})">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            @else
                                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                        wire:click="toggleCompletedOnInfo({{ $anime->id }})">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            @if($anime->favorite)
                                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-pink-600 dark:hover:text-pink-400 rounded-full transition-colors cursor-pointer"
                                        wire:click="toggleFavoriteOnInfo({{ $anime->id }})">
                                    <i class="fas fa-heart-circle-minus"></i>
                                </button>
                            @else
                                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-pink-600 dark:hover:text-pink-400 rounded-full transition-colors cursor-pointer"
                                        wire:click="toggleFavoriteOnInfo({{ $anime->id }})">
                                    <i class="fas fa-heart"></i>
                                </button>
                            @endif
                            @if($anime->watching)
                                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 rounded-full transition-colors cursor-pointer"
                                        wire:click="toggleWatchingOnInfo({{ $anime->id }})">
                                    <i class="fas fa-stop"></i>
                                </button>
                            @else
                                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 rounded-full transition-colors cursor-pointer"
                                        wire:click="toggleWatchingOnInfo({{ $anime->id }})">
                                    <i class="fas fa-play"></i>
                                </button>
                            @endif
                        </div>
                        <button wire:click="closeModal"
                                class="text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300 text-xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center px-6 py-2 border-b border-neutral-200 dark:border-neutral-700 space-y-2">
                    <div>
                        @if($anime->title_en)
                            <h3 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">{{ $anime->title_en }}</h3>
                        @endif
                        @if($anime->title_jp)
                            <p class="text-lg text-neutral-600 dark:text-neutral-400">{{ $anime->title_jp }}</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0 text-center">
                        <i class="fas fa-star text-yellow-500"></i>
                        <span class="text-lg text-yellow-500">{{ $anime->score }}</span>
                        <br>
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">
                            ({{ $anime->num_scoring_usr }})
                        </span>
                    </div>
                </div>

                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Cover -->
                        <div class="flex-shrink-0">
                            <img src="{{ $anime->cover() }}"
                                 alt="{{ $anime->title }}"
                                 class="h-64 rounded-lg shadow-md hover:scale-125 transition-transform duration-200">
                        </div>

                        <!-- Images -->
                        <div class="w-full flex gap-2 flex-wrap justify-center items-center">
                            @foreach($anime->images as $image)
                                @if($loop->first)
                                    @continue
                                @endif

                                <img src="{{ $image->url }}"
                                     alt="{{ $anime->title }} image"
                                     class="h-40 rounded-lg shadow-sm hover:scale-200 transition-transform duration-200">
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- content -->
                <div class="p-6">
                    <div class="space-y-8">
                        <!-- Media Information Row -->
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 justify-center items-center">
                            <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-xl">
                                <div class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">{{ strtoupper($anime->media_type) }}</div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Type</div>
                            </div>

                            <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-xl">
                                <div class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">{{ $anime->num_episodes }}</div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Episodes</div>
                            </div>

                            <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-xl">
                                <div class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">{{ $anime->average_ep_duration }}
                                    minutes
                                </div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                    Average ep. duration
                                </div>
                            </div>

                            <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-xl">
                                <div class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">{{ ucfirst(str_replace("_", " ", $anime->status)) }}</div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Status</div>
                            </div>

                            <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-xl col-span-2 md:col-span-1">
                                <div class="text-2xl font-bold
                                        {{ $anime->nsfw == $anime::NSFW_BLACK ? 'text-rose-600 dark:text-rose-400' :
                                           ($anime->nsfw == $anime::NSFW_GRAY ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400') }}">
                                    {{ $anime->nsfw == $anime::NSFW_BLACK ? 'NSFW' :
                                       ($anime->nsfw == $anime::NSFW_GRAY ? 'Mature' : 'Safe') }}
                                </div>
                                <div class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Rating</div>
                            </div>
                        </div>

                        <!-- Dates Row -->
                        <div class="flex flex-wrap gap-4 justify-center">
                            @if($anime->start_date)
                                <div class="flex items-center bg-neutral-50 dark:bg-neutral-800 rounded-full px-6 py-3">
                                    <i class="fas fa-calendar-plus text-green-500 mr-3"></i>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400 mr-2">Started:</span>
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $anime->start_date }}</span>
                                </div>
                            @endif

                            @if($anime->end_date)
                                <div class="flex items-center bg-neutral-50 dark:bg-neutral-800 rounded-full px-6 py-3">
                                    <i class="fas fa-calendar-check text-red-500 mr-3"></i>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400 mr-2">Ended:</span>
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200">{{ $anime->end_date }}</span>
                                </div>
                            @endif

                            @if($anime->broadcast_weekday || $anime->broadcast_time)
                                <div class="flex items-center bg-neutral-50 dark:bg-neutral-800 rounded-full px-6 py-3">
                                    <i class="fas fa-clock text-blue-500 mr-3"></i>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400 mr-2">Broadcast:</span>
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200">
                                        {{ $anime->broadcast_weekday ? $anime->broadcast_weekday . ' ' : '' }}
                                        {{ $anime->broadcast_time ? $anime->broadcast_time : '' }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="w-full flex justify-center items-center">
                            <div class="text-center p-4 bg-neutral-50 dark:bg-neutral-800 rounded-xl">
                                <div class="flex text-2xl font-bold text-yellow-500">
                                    <i class="fa fa-star font-medium"></i>
                                    <input type="number" step="0.1" placeholder="00.0"
                                           wire:model="personalScore" id="personalScore"
                                           size="4"
                                           oninput="this.style.width = (this.value.length + 1) + 'ch'"
                                           class="appearance-textfield text-center ms-2 w-fit border-0 border-b-2 border-b-yellow-500 placeholder-neutral-400 dark:placeholder-neutral-500 text-lg">
                                </div>
                                <lable for="personalScore" class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                    Personal Score
                                </lable>
                            </div>
                        </div>

                        <!-- Genres -->
                        @if($anime->genres && $anime->genres->count() > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4 flex items-center">
                                    <i class="fas fa-tags text-neutral-500 mr-2"></i>
                                    Genres
                                </h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($anime->genres as $genre)
                                        <span class="px-4 py-2 bg-neutral-200 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 rounded-full text-sm font-medium shadow-sm border border-neutral-200 dark:border-neutral-600">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Synopsis -->
                        @if($anime->synopsis)
                            <div x-data="{ expanded: false }">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="cursor-pointer text-lg font-semibold text-neutral-900 dark:text-neutral-100 flex items-center"
                                        @click="expanded = !expanded">
                                        <i class="fas fa-book-open text-neutral-500 mr-2"></i>
                                        Synopsis
                                    </h3>
                                    <button @click="expanded = !expanded"
                                            class="cursor-pointer flex items-center text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-200 transition-colors">
                                        <span x-text="expanded ? 'Hide Synopsis' : 'Show Synopsis'"></span>
                                        <i class="fas fa-chevron-down ml-2 transition-transform duration-200"
                                           :class="{ 'rotate-180': expanded }"></i>
                                    </button>
                                </div>
                                <div class="bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 rounded-xl p-6 border border-neutral-200 dark:border-neutral-700"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 max-h-0"
                                     x-transition:enter-end="opacity-100 max-h-screen"
                                     x-transition:leave-start="opacity-100 max-h-screen"
                                     x-transition:leave-end="opacity-0 max-h-0"
                                     x-show="expanded">
                                    <div class="overflow-hidden">
                                        <p class="text-neutral-700 dark:text-neutral-300 leading-relaxed text-justify">
                                            {{ $anime->synopsis }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($anime->background)
                            <div x-data="{ expanded: false }">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="cursor-pointer text-lg font-semibold text-neutral-900 dark:text-neutral-100 flex items-center"
                                        @click="expanded = !expanded">
                                        <i class="fas fa-info-circle text-neutral-500 mr-2"></i>
                                        Background
                                    </h3>
                                    <button @click="expanded = !expanded"
                                            class="cursor-pointer flex items-center text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-200 transition-colors">
                                        <span x-text="expanded ? 'Hide Background' : 'Show Background'"></span>
                                        <i class="fas fa-chevron-down ml-2 transition-transform duration-200"
                                           :class="{ 'rotate-180': expanded }"></i>
                                    </button>
                                </div>
                                <div class="bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 rounded-xl p-6 border border-neutral-200 dark:border-neutral-700 relative"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 max-h-0"
                                     x-transition:enter-end="opacity-100 max-h-screen"
                                     x-transition:leave-start="opacity-100 max-h-screen"
                                     x-transition:leave-end="opacity-0 max-h-0"
                                     x-show="expanded">
                                    <div class="overflow-hidden">
                                        <p class="text-neutral-700 dark:text-neutral-300 leading-relaxed text-justify">
                                            {{ $anime->background }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4 flex items-center">
                                <i class="fas fa-note-sticky text-neutral-500 mr-2"></i>
                                Notes
                            </h3>
                            <div class="mt-6">
                                <textarea wire:model="notes"
                                          placeholder="Write something about this anime..."
                                          class="w-full px-4 py-3 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-neutral-400 dark:focus:ring-neutral-500 focus:border-transparent text-neutral-800 dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500 h-32 resize-none"></textarea>
                            </div>
                        </div>

                        @if($anime->relatedAnimes)
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4 flex items-center">
                                    <i class="fas fa-link text-neutral-500 mr-2"></i>
                                    Related Animes
                                </h3>
                                <div class="grid grid-cols-2 bg-neutral-100 dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-visible">
                                    @foreach($anime->relatedAnimes as $related)
                                        <div class="{{ $loop->even ?: "border-r" }} group flex items-center p-4 border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors overflow-visible"
                                             x-data="{ showNotePopup: false }">
                                            <div class="flex items-center space-x-2 mr-4 text-lg">
                                                @if($related->alreadyOnList)
                                                    <button class="p-2 text-neutral-600 dark:text-neutral-400 transition-colors cursor-pointer">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @else
                                                    <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                                            wire:click="addToList({{ $related->id }})">
                                                        <i class="fas fa-add"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="w-fit h-32 flex-shrink-0 mr-4">
                                                <img
                                                        src="{{ $related->image }}"
                                                        alt="Anime Cover"
                                                        class="w-full h-full object-cover rounded"
                                                >
                                            </div>
                                            <div class="flex-grow">
                                                <h3 class="font-semibold text-lg text-neutral-800 dark:text-neutral-100">{{ $related->title }}</h3>
                                            </div>


                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
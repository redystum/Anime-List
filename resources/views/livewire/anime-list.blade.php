<div id="{{ $list_name }}" class="scroll-mt-20">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="#{{ $list_name }}" class="flex items-center space-x-4 text-xl">
                <i class="fas fa-{{ $icon }}"></i>
                <p>{{ $title }}</p>
            </a>
        </div>
        <div class="flex space-x-2">
            <select class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    wire:model.live="sort">
                <option value="recently_added">Recently added</option>
                <option value="oldest_added">Oldest added</option>
                <option value="recent">Recent</option>
                <option value="oldest">Oldest</option>
                <option value="az">A-Z</option>
                <option value="za">Z-A</option>
                <option value="highest_rated">Highest rated</option>
                <option value="lowest_rated">Lowest rated</option>
                <option value="more_episodes">More Episodes</option>
                <option value="less_episodes">Less Episodes</option>
                <option value="most_favorite">Most Favorite</option>
                <option value="least_favorite">Least Favorite</option>
            </select>
            @if($haveAdd)
                <button class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg transition-colors"
                        onclick="focusOnSearch()">
                    <i class="fas fa-plus"></i>
                    Add Anime
                </button>
            @endif
        </div>
    </div>

    <div class="bg-neutral-100 dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-visible">
        @forelse($animes as $anime)
            <div id="anime-{{ $anime->id }}" class="group flex items-center p-4 border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors overflow-visible"
                 x-data="{ showNotePopup: false }">
                <div class="flex items-center space-x-2 mr-4 text-lg">
                    @if($anime->completed)
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                wire:click="toggleCompleted({{ $anime->id }})">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    @else
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                wire:click="toggleCompleted({{ $anime->id }})">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                    @if($anime->favorite)
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-pink-600 dark:hover:text-pink-400 rounded-full transition-colors cursor-pointer"
                                wire:click="toggleFavorite({{ $anime->id }})">
                            <i class="fas fa-heart-circle-minus"></i>
                        </button>
                    @else
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-pink-600 dark:hover:text-pink-400 rounded-full transition-colors cursor-pointer"
                                wire:click="toggleFavorite({{ $anime->id }})">
                            <i class="fas fa-heart"></i>
                        </button>
                    @endif
                    @if($anime->watching)
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 rounded-full transition-colors cursor-pointer"
                                wire:click="toggleWatching({{ $anime->id }})">
                            <i class="fas fa-stop"></i>
                        </button>
                    @else
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 rounded-full transition-colors cursor-pointer"
                                wire:click="toggleWatching({{ $anime->id }})">
                            <i class="fas fa-play"></i>
                        </button>
                    @endif
                </div>

                <div class="w-fit h-32 flex-shrink-0 mr-4">
                    <img
                            src="{{ $anime->cover() }}"
                            alt="Anime Cover"
                            class="w-full h-full object-cover rounded"
                    >
                </div>
                <div class="flex-grow">
                    <h3 class="font-semibold text-lg text-neutral-800 dark:text-neutral-100">
                        {{ $anime->title }}
                        @if(session("markAiring", false))
                            @if($anime->status == $anime::STATUS_AIRING)
                                <i class="fas fa-circle-play ms-6"></i>
                            @elseif($anime->status == $anime::STATUS_NOT_YET_AIRED)
                                <i class="fas fa-circle-plus rotate-45 ms-6"></i>
                            @endif
                        @endif
                    </h3>
                    <div class="flex items-center mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        <span>{{ strtoupper($anime->media_type) }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $anime->num_episodes }} Episodes</span>
                        <span class="mx-2">•</span>
                        @if($anime->nsfw == $Anime::NSFW_BLACK)
                            <span class="text-red-600/80 dark:text-red-400/80">NSFW</span>
                            <span class="mx-2">•</span>
                        @elseif($anime->nsfw == $Anime::NSFW_GRAY)
                            <span class="text-yellow-600/80 dark:text-yellow-400/80">Mature</span>
                            <span class="mx-2">•</span>
                        @endif
                        <span>{{ implode(', ', $anime->genres->pluck('name')->toArray()) }}</span>
                    </div>
                    <div class="mt-2 flex items-center">
                        <div class="flex items-center text-yellow-500">
                            <i class="fas fa-star"></i>
                            <span class="ml-1 text-sm font-medium">{{ $anime->score }}</span>
                            <span class="text-neutral-400 text-sm ms-2">({{ $anime->num_scoring_usr }})</span>
                        </div>
                        @if($anime->completed)
                            <span class="mx-2 text-neutral-400">•</span>
                            <span class="text-green-600/80 dark:text-green-400/80">Completed</span>

                            @if($anime->localScore)
                                <span class="mx-2 text-neutral-400">•</span>
                                <span class="text-yellow-600/80 dark:text-yellow-400/80">Personal Score: {{ $anime->localScore }}</span>
                            @endif
                        @elseif($anime->watching)
                            <span class="mx-2 text-neutral-400">•</span>
                            <span class="text-blue-600/80 dark:text-blue-400/80">Watching</span>
                        @endif
                    </div>
                </div>

                @if($anime->notes)
                    <div class="relative">
                        <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                                @click="showNotePopup = true">
                            <i class="fas fa-note-sticky"></i>
                        </button>

                        <!-- Note Popup -->
                        <div x-show="showNotePopup"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="showNotePopup = false"
                             class="absolute right-0 bottom-full mt-2 w-80 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-lg p-4 z-50">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-neutral-800 dark:text-neutral-200">Notes</h4>
                                <button @click="showNotePopup = false"
                                        class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-300">
                                <p class="whitespace-pre-wrap">{{ $anime->notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="md:opacity-0 space-x-2 text-lg md:group-hover:opacity-100 flex">

                    <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer"
                            wire:click="$dispatch('show-info-modal', { animeId: {{ $anime->id }} })">
                        <i class="fas fa-info-circle"></i>
                    </button>

                    <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-400 rounded-full transition-colors cursor-pointer"
                            wire:click="$dispatch('show-delete-dialog', { animeId: {{ $anime->id }}, animeTitle: '{{ addslashes($anime->title) }}' })">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        @empty
            <div class="p-4 text-center text-neutral-500 dark:text-neutral-400">
                <p>No animes found in this list.</p>
            </div>
        @endforelse

        @if(count($animes) > 0 && session("showStats", false))
            <div class="flex justify-between items-center p-4 text-sm text-neutral-600 dark:text-neutral-300">
                <div class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ count($animes) }} {{ strtolower($title) }}
                </div>
                <div>
                    {{ $total_time }} total duration
                </div>
                <div>
                    {{ $total_eps }} episodes
                </div>
            </div>
        @endif

    </div>

</div>
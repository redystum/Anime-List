<div class="relative w-full max-w-lg mx-auto" x-data @click.away="$wire.closeDropdown()">
    <input
            type="text"
            id="searchInput"
            autofocus
            wire:model.live.debounce.300ms="query"
            wire:keydown.escape="resetInput"
            wire:keydown.enter="selectItem"
            wire:keydown.arrow-down.prevent="incrementIndex"
            wire:keydown.arrow-up.prevent="decrementIndex"
            wire:keydown.tab.prevent="incrementIndex"
            wire:focus="openDropdown"
            placeholder="Search products & categories..."
            class="cursor-pointer w-full pl-10 pr-4 py-2 rounded-xl border border-gray-300 dark:border-neutral-700
            focus:outline-none focus:border-1 focus:border-purple-500 text-sm bg-white dark:bg-neutral-800
            text-gray-900 dark:text-neutral-100 transition-none
            @if(strlen($query) > 0 && ($results || $results_pages))
            !rounded-b-none !border-1 !border-purple-500 outline-none !border-b-neutral-700
            @endif"
    />
    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-neutral-500 text-sm"></i>

    @if(strlen($query) > 0 && ($results || $results_pages))
        <div id="searchDropdown"
             class="absolute left-0 w-full bg-white dark:bg-neutral-800 rounded-b-xl shadow-lg border border-purple-500 dark:border-purple-500 border-t-0 z-50 max-h-dvh overflow-y-auto transition-opacity duration-200"
        >
            @if($results && $results_pages && $results->isEmpty() && $results_pages->isEmpty())
                <div class="px-4 py-2 text-sm text-gray-500">No results found.</div>
            @else
                @foreach($results as $index => $result)
                    <div
                            class="cursor-pointer flex items-center justify-between px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 {{ $selectedIndex === $index ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                            wire:click.prevent="selectItem({{ $index }})"
                            wire:key="search-result-{{ $index }}"
                    >
                        <div class="flex items-center">
                            <div
                                    class="flex items-center justify-center">
                                <img src="{{ $result->cover() }}" alt="" class="h-18 mr-3"/>
                            </div>
                            <div>
                                <div class="text-lg text-neutral-100">{{ $result->title }}</div>
                                <div class="flex items-center mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                    <span>{{ strtoupper($result->media_type) }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $result->num_episodes }} Eps.</span>
                                    <span class="mx-2">•</span>
                                    @if($result->nsfw == $Anime::NSFW_BLACK)
                                        <span class="text-red-600/80 dark:text-red-400/80">NSFW</span>
                                        <span class="mx-2">•</span>
                                    @elseif($result->nsfw == $Anime::NSFW_GRAY)
                                        <span class="text-yellow-600/80 dark:text-yellow-400/80">Mature</span>
                                        <span class="mx-2">•</span>
                                    @endif
                                    <span>
                                        {{ implode(', ', $result->genres->pluck('name')->take(2)->toArray()) }}
                                        @if($result->genres->count() > 2)
                                            ...
                                        @endif
                                    </span></div>
                                <div class="mt-2 flex items-center">
                                    <div class="flex items-center text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <span class="ml-1 text-sm font-medium">{{ $result->score }}</span>
                                        <span class="text-neutral-400 text-sm ms-2">
                                            ({{ $result->num_scoring_usr }})
                                        </span>
                                    </div>
                                    @if($result->completed)
                                        <span class="mx-2 text-neutral-400">•</span>
                                        <span class="text-green-600/80 dark:text-green-400/80">Completed</span>

                                        @if($result->localScore)
                                            <span class="mx-2 text-neutral-400">•</span>
                                            <span class="text-yellow-600/80 dark:text-yellow-400/80">
                                                Personal Score: {{ $result->localScore }}
                                            </span>
                                        @endif
                                    @elseif($result->watching)
                                        <span class="mx-2 text-neutral-400">•</span>
                                        <span class="text-blue-600/80 dark:text-blue-400/80">Watching</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div>
                            @if($result->favorite)
                                <div class="p-2 text-pink-600 dark:text-pink-400">
                                    <i class="fas fa-heart"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                @foreach($results_pages as $index => $result)
                    <div
                            class="cursor-pointer flex items-center px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 {{ $selectedIndex === ($index + count($results)) ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                            wire:click.prevent="selectPage({{ $index }})"
                            wire:key="search-result-{{ $index + count($results) }}"
                    >
                        <div
                                class="h-8 w-8 mr-3 rounded-full overflow-hidden bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                            <i class="fas {{ $result->icon }} text-neutral-500"></i>
                        </div>
                        <div>
                            <div class="text-lg text-neutral-100">{{ $result->name }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
            <div
                    class="cursor-pointer flex items-center px-4 py-2 text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 {{ $selectedIndex === (count($results) + count($results_pages)) ? 'bg-gray-100 dark:bg-neutral-700' : '' }}"
                    wire:click.prevent="addAnime()"
                    wire:key="search-result-add-anime"
            >
                <div
                        class="h-8 w-8 mr-3 rounded-full overflow-hidden bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center">
                    <i class="fas fa-search-plus text-neutral-500"></i>
                </div>
                <div>
                    <div class="text-lg text-neutral-100">{{ $query }}</div>
                    <div class="text-xs text-gray-500 dark:text-neutral-400">
                        Search and add this anime to your list.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
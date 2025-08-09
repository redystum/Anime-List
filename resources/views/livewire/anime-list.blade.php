<div id="{{ $list_name }}" class="scroll-mt-20">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="#{{ $list_name }}" class="flex items-center space-x-4 text-xl">
                <i class="fas fa-{{ $icon }}"></i>
                <p>{{ $title }}</p>
            </a>
        </div>
        <div class="flex space-x-2">
            <select class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Recently added</option>
                <option>Oldest added</option>
                <option>Recent</option>
                <option>Oldest</option>
                <option>A-Z</option>
                <option>Z-A</option>
                <option>Highest rated</option>
                <option>Lowest rated</option>
                <option>More Episodes</option>
                <option>Less Episodes</option>
            </select>
            <button class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg transition-colors">
                <i class="fas fa-plus"></i>
                Add Anime
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="flex items-center p-4 border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
            <div class="w-16 h-24 flex-shrink-0 mr-4">
                <img
                        src="https://via.placeholder.com/64x96"
                        alt="Anime Cover"
                        class="w-full h-full object-cover rounded"
                >
            </div>
            <div class="flex-grow">
                <h3 class="font-semibold text-neutral-800 dark:text-neutral-100">Attack on Titan: Final Season</h3>
                <div class="flex items-center mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    <span>TV</span>
                    <span class="mx-2">•</span>
                    <span>24 Episodes</span>
                    <span class="mx-2">•</span>
                    <span>Action, Drama</span>
                </div>
                <div class="mt-2 flex items-center">
                    <div class="flex items-center text-yellow-500">
                        <i class="fas fa-star"></i>
                        <span class="ml-1 text-sm font-medium">8.95</span>
                    </div>
                    <span class="mx-2 text-neutral-400">•</span>
                    <span class="text-sm text-blue-600 dark:text-blue-400">Watching</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer">
                    <i class="fas fa-info-circle"></i>
                </button>
                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-full transition-colors cursor-pointer">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="p-2 text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-400 rounded-full transition-colors cursor-pointer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>

    </div>
</div>
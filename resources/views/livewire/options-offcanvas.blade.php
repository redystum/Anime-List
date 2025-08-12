<div x-data="{ 
    isOpen: @entangle('open').live,
    closeOffcanvas() {
        this.isOpen = false;
        $wire.closeOffcanvas();
    }
}"
     x-show="isOpen"
     x-cloak
     class="fixed inset-0 overflow-hidden z-50">

    <!-- overlay -->
    <div x-show="isOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm"
         @click="closeOffcanvas()">
    </div>

    <div x-show="isOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="z-50 fixed inset-y-0 right-0 w-full max-w-md bg-neutral-50 dark:bg-neutral-800 shadow-xl overflow-y-auto">

        <div class="p-6 h-full">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">
                    <i class="fas fa-cog mr-2"></i> Settings
                </h2>
                <button @click="closeOffcanvas()"
                        class="cursor-pointer text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300 p-2 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-8 h-full flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-neutral-700 dark:text-neutral-200 mb-6 flex items-center">
                        <i class="fas fa-sliders-h mr-2 text-neutral-500"></i> General
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-thumbtack text-neutral-700 dark:text-neutral-300 mr-3 rotate-45"></i>
                                <div>
                                    <p class="text-neutral-700 dark:text-neutral-300 font-medium">
                                        Pin Navbar
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Pin the navigation bar to the top of the page
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" wire:model.live="pinNav">
                                <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-sync-alt text-neutral-700 dark:text-neutral-300 mr-3"></i>
                                <div>
                                    <p class="text-neutral-700 dark:text-neutral-300 font-medium">
                                        Auto Update on Info Open
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Fetch latest data when opening anime info
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" wire:model.live="autoUpdate">
                                <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-circle-play text-neutral-700 dark:text-neutral-300 mr-3"></i>
                                <div>
                                    <p class="text-neutral-700 dark:text-neutral-300 font-medium">
                                        Mark Anime Still Airing
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Mark airing and upcoming anime with
                                        <i class="fas fa-circle-play"></i> and <i class="fas fa-circle-plus rotate-45 "></i>
                                        respectively
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" wire:model.live="markAiring">
                                <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-chart-line text-neutral-700 dark:text-neutral-300 mr-3"></i>
                                <div>
                                    <p class="text-neutral-700 dark:text-neutral-300 font-medium">
                                        Show List Statistics
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Display stats about the lists (count, eps, duration, etc.)
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" wire:model.live="showStats">
                                <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-3 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Danger Zone
                    </h3>
                    <div class="space-y-3 border border-red-200 dark:border-red-900 rounded-lg p-4 bg-red-50 dark:bg-red-900/20">
                        <button wire:click="exportDatabase"
                                class="cursor-pointer w-full flex items-center justify-between p-3 bg-white dark:bg-neutral-700 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-600 transition-colors">
                            <div class="flex items-center">
                                <i class="fas fa-file-export text-neutral-700 dark:text-neutral-200 mr-3"></i>
                                <span class="text-neutral-700 dark:text-neutral-200 font-medium">Export Database</span>
                            </div>
                            <i class="fas fa-chevron-right text-neutral-400"></i>
                        </button>

                        <button wire:click="importDatabase"
                                class="cursor-pointer w-full flex items-center justify-between p-3 bg-white dark:bg-neutral-700 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-600 transition-colors">
                            <div class="flex items-center">
                                <i class="fas fa-file-import text-neutral-700 dark:text-neutral-200 mr-3"></i>
                                <span class="text-neutral-700 dark:text-neutral-200 font-medium">Import Database</span>
                            </div>
                            <i class="fas fa-chevron-right text-neutral-400"></i>
                        </button>

                        <button wire:click="confirmDelete"
                                class="cursor-pointer w-full flex items-center justify-between p-3 bg-red-100 dark:bg-red-900/50 text-neutral-50 dark:text-neutral-50 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                            <div class="flex items-center">
                                <i class="fas fa-trash-alt mr-3"></i>
                                <span class="font-medium">Delete Database</span>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
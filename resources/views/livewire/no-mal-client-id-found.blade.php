<div x-data="{ show: @entangle('show') }" x-cloak>
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
         wire:click="closeDialog">
        <div class="bg-white dark:bg-neutral-900 rounded-lg p-6 max-w-md mx-4 border border-neutral-200 dark:border-neutral-700"
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.stop>
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-2">
                    MyAnimeList API Client ID Not Found
                </h3>
                <div class="text-neutral-600 dark:text-neutral-400 mb-6 text-left">
                    <p class="mb-3">
                        The MyAnimeList API Client ID was not found in the .env file.
                    </p>
                    <p class="mb-4">
                        If you want, you can enter the Client ID here:
                    </p>
                    
                    <div class="mb-4">
                        <input type="text" 
                               wire:model="clientId"
                               placeholder="Enter MAL Client ID"
                               class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-sm mt-0.5 mr-2"></i>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                <strong>Warning:</strong> The Client ID entered here is not encrypted when saved on the database.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="closeDialog"
                            class="cursor-pointer flex-1 px-4 py-2 bg-neutral-200 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 rounded-lg hover:bg-neutral-300 dark:hover:bg-neutral-600 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="saveClientId"
                            class="cursor-pointer flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Save & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
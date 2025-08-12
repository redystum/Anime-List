<nav class="bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700 shadow-sm fixed top-0 z-40 w-full">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-6">
                <a href="#" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                         class="h-8 w-8">
                    <span class="ml-2 text-xl font-bold text-neutral-800 dark:text-neutral-100 text-nowrap">
                        {{ config('app.name') }}
                    </span>
                </a>
            </div>

            <div class="hidden lg:block w-full">
                <livewire:search-dropdown/>
            </div>

            <div>
                <ul class="flex items-center space-x-4">
                    <li>
                        <a href="#{{$Anime::LIST_WATCHING}}"
                           class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-blue-500 dark:hover:text-blue-400">
                            <i class="fas fa-eye"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#{{$Anime::LIST_WATCH}}"
                           class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-blue-500 dark:hover:text-blue-400">
                            <i class="fas fa-tv"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#{{$Anime::LIST_WATCHED}}"
                           class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-blue-500 dark:hover:text-blue-400">
                            <i class="fas fa-check"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#{{$Anime::LIST_FAVORITE}}"
                           class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-blue-500 dark:hover:text-blue-400">
                            <i class="fas fa-heart"></i>
                        </a>
                    </li>
                    <li>
                        <div id="openOptions"
                             class="cursor-pointer ms-6 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-blue-500 dark:hover:text-blue-400 hover:animate-spin">
                            <i class="fas fa-gear"></i>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <div class="flex-1 w-full my-4 block lg:hidden">
            <livewire:search-dropdown/>
        </div>
    </div>

</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openOptionsBtn = document.getElementById('openOptions');
        if (openOptionsBtn) {
            openOptionsBtn.addEventListener('click', function () {
                window.Livewire.dispatch('openOptions');
            });
        }

        // Handle smooth scrolling with offset for anchor links
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a[href^="#"]');
            if (link) {
                const href = link.getAttribute('href');
                const targetId = href.substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    e.preventDefault();
                    const navHeight = navbar.offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - navHeight - 20; // 20px extra padding

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL hash
                    history.pushState(null, null, href);
                }
            }
        });

        // Handle initial page load with hash
        if (window.location.hash) {
            setTimeout(() => {
                const targetElement = document.querySelector(window.location.hash);
                if (targetElement) {
                    const navHeight = navbar.offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - navHeight - 20;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }, 100);
        }

        window.Livewire.on('scroll-to', (params) => {
            const [url, isAnime] = params;
            const element = document.querySelector(url);

            if (element) {
                const navHeight = navbar.offsetHeight;
                const elementPosition = element.getBoundingClientRect().top;
                const screenHeight = window.innerHeight;

                let offsetPosition;
                if (isAnime) {
                    offsetPosition = elementPosition + window.pageYOffset - screenHeight / 2 + 20;
                } else {
                    offsetPosition = elementPosition + window.pageYOffset - navHeight - 20;
                }

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                if (isAnime) {
                    element.classList.add('highlight');
                    setTimeout(() => {
                        element.classList.remove('highlight');
                    }, 2000);
                }

            }
        });

        const navbar = document.querySelector('nav');

        function updateBodyPadding(pinned) {
            const navHeight = navbar.offsetHeight;
            if (pinned) {
                document.body.style.paddingTop = navHeight + 'px';
                document.documentElement.style.scrollPaddingTop = navHeight + 'px';
            } else {
                document.body.style.paddingTop = '0';
                document.documentElement.style.scrollPaddingTop = navHeight + 'px';
            }
        }

        function setState(pinned) {
            if (pinned) {
                navbar.classList.remove('relative');
                navbar.classList.add('fixed');
            } else {
                navbar.classList.remove('fixed');
                navbar.classList.add('relative');
            }
            updateBodyPadding(pinned);
        }

        window.Livewire.on('pinNavChanged', (params) => {
            let pin = params[0];
            if (pin) {
                setState(true);
            } else {
                setState(false);
            }
        });

        const isPinned = @js(session('pinNav', true));
        setState(isPinned);
    });
</script>
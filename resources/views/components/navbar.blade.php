<nav class="bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700 shadow-sm fixed top-0 z-50 w-full">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-6">
                <a href="#" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                         class="h-8 w-8">
                    <span class="ml-2 text-xl font-bold text-neutral-800 dark:text-neutral-100">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- TODO: Replace this search by the other one on the other project #recycle --}}
            <div class="flex-1 max-w-xl ms-4 hidden md:block">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                            type="text"
                            placeholder="Search or add anime..."
                            class="block w-full pl-10 pr-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-md bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
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
                </ul>
            </div>

        </div>

        <div class="flex-1 max-w-xl my-4 block md:hidden">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                        type="text"
                        placeholder="Search or add anime..."
                        class="block w-full pl-10 pr-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-md bg-white dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
        </div>
    </div>

    <div class="absolute top-0 right-0 z-50 h-full me-8 md:flex items-center justify-center hidden text-neutral-900/20 dark:text-neutral-300/20 hover:text-neutral-900/100 hover:dark:text-neutral-300/100 transition-colors duration-200">
        <i class="fas fa-thumbtack-slash cursor-pointer" id="pinNav"></i>
        <i class="fas fa-thumbtack cursor-pointer" id="unpinNav"></i>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pinNav = document.getElementById('pinNav');
        const unpinNav = document.getElementById('unpinNav');
        const navbar = document.querySelector('nav');
        const navStateKey = 'navbarPinned';

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
                pinNav.style.display = 'none';
                unpinNav.style.display = 'block';
                navbar.classList.remove('relative');
                navbar.classList.add('fixed');
            } else {
                pinNav.style.display = 'block';
                unpinNav.style.display = 'none';
                navbar.classList.remove('fixed');
                navbar.classList.add('relative');
            }
            updateBodyPadding(pinned);
            localStorage.setItem(navStateKey, pinned ? 'true' : 'false');
        }

        const savedState = localStorage.getItem(navStateKey);
        const isPinned = savedState !== 'false';
        setState(isPinned);

        pinNav.addEventListener('click', function () {
            setState(true);
        });

        unpinNav.addEventListener('click', function () {
            setState(false);
        });

        window.addEventListener('resize', function () {
            const currentState = localStorage.getItem(navStateKey) !== 'false';
            updateBodyPadding(currentState);
        });

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
    });
</script>
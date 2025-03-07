<nav x-data="{ open: false }" class="bg-white border-bottom border-light">
    <!-- Primary Navigation Menu -->
    <div class="container-lg px-4 py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <!-- Logo -->
                <div class="shrink-0 d-flex align-items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="h-9 w-auto text-dark" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="d-none d-sm-flex ms-3">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="me-4">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')" class="me-4">
                        {{ __('Permissions') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="d-none d-sm-flex align-items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="d-flex align-items-center px-3 py-2 border-0 text-sm font-medium text-gray-500 bg-white hover:text-gray-700 transition duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                          this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="d-flex d-sm-none">
                <button @click="open = ! open" class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'d-none': open, 'd-inline-flex': ! open }" class="d-inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'d-none': ! open, 'd-inline-flex': open }" class="d-none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'d-block': open, 'd-none': ! open}" class="d-none d-sm-none">
        <div class="pt-2 pb-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="d-block py-2">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-top border-light">
            <div class="px-4">
                <div class="fw-medium text-dark">{{ Auth::user()->name }}</div>
                <div class="fw-medium text-muted">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="d-block py-2">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                      this.closest('form').submit();"
                            class="d-block py-2">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

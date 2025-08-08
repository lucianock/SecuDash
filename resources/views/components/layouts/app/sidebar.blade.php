<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-dark-bg dark:bg-dark-bg text-dark-text dark:text-dark-text">
        <flux:sidebar sticky stashable class="border-r border-dark-border bg-dark-surface dark:border-dark-border dark:bg-dark-surface">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline" class="dark">
                <flux:navlist.group heading="Platform" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="dark:text-dark-text hover:bg-dark-surface-2">
                        {{ __('Dashboard') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="shield-check" :href="route('vulnerability.index')" :current="request()->routeIs('vulnerability.*')" wire:navigate class="dark:text-dark-text hover:bg-dark-surface-2">
                        {{ __('Vulnerability Scanner') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="key" :href="route('password-generator')" :current="request()->routeIs('password-generator')" wire:navigate class="dark:text-dark-text hover:bg-dark-surface-2">
                        {{ __('Password Generator') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="bug-ant" :href="route('vulnerability-search')" :current="request()->routeIs('vulnerability-search')" wire:navigate class="dark:text-dark-text hover:bg-dark-surface-2">
                        {{ __('Vulnerability Search') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="key" :href="route('vault.index')" :current="request()->routeIs('vault.*')" wire:navigate class="dark:text-dark-text hover:bg-dark-surface-2">
                        {{ __('Vault') }}
                    </flux:navlist.item>

                    {{-- !! Under development --}}
                    {{-- <flux:navlist.item icon="magnifying-glass" :href="route('linkedin.index')" :current="request()->routeIs('linkedin-search.*')" wire:navigate>
                        {{ __('Scraping') }}
                    </flux:navlist.item> --}}
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            {{-- <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist> --}}

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                    class="dark:text-dark-text"
                />

                <flux:menu class="w-[220px] dark:bg-dark-surface dark:border-dark-border">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-dark-surface-2 text-dark-text dark:bg-dark-surface-2 dark:text-dark-text">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold dark:text-dark-text">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs dark:text-dark-text-secondary">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator class="dark:border-dark-border" />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate class="dark:text-dark-text hover:bg-dark-surface-2">
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator class="dark:border-dark-border" />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full dark:text-dark-text hover:bg-dark-surface-2">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <flux:main class="bg-dark-bg dark:bg-dark-bg">
            <div class="content-area">
                {{ $slot }}
            </div>
        </flux:main>
    </body>
</html>

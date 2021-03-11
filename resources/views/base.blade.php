<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Scripts Dashboard</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.0.1/dist/alpine.js" defer=""></script>
</head>
<body>
<div class="h-screen flex overflow-hidden bg-gray-100" x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">
    <div x-show="sidebarOpen" class="md:hidden" x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state.">
        <div class="fixed inset-0 flex z-40">
            <transition enter-active-class="transition-opacity ease-linear duration-300" enter-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-opacity ease-linear duration-300" leave-class="opacity-100" leave-to-class="opacity-0"><div @click="sidebarOpen = false" x-show="sidebarOpen" x-description="Off-canvas menu overlay, show/hide based on off-canvas menu state." class="fixed inset-0" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div></transition>
            <transition enter-active-class="transition ease-in-out duration-300 transform" enter-class="-translate-x-full" enter-to-class="translate-x-0" leave-active-class="transition ease-in-out duration-300 transform" leave-class="translate-x-0" leave-to-class="-translate-x-full"><div x-show="sidebarOpen" x-description="Off-canvas menu, show/hide based on off-canvas menu state." class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button x-show="sidebarOpen" @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-shrink-0 flex items-center px-4">
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-800-text.svg" alt="Workflow">
                    </div>
                    <div class="mt-5 flex-1 h-0 overflow-y-auto">
                        <nav class="px-2 space-y-1">


                            <!-- Current: "bg-gray-100 text-gray-900", Default: "text-gray-600 hover:bg-gray-50 hover:text-gray-900" -->
                            <a href="#" class="bg-gray-100 text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <!-- Current: "text-gray-500", Default: "text-gray-400 group-hover:text-gray-500" -->
                                <svg class="text-gray-500 mr-4 h-6 w-6" x-description="Heroicon name: outline/home" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>
                        </nav>
                    </div>
                </div></transition>
            <div class="flex-shrink-0 w-14" aria-hidden="true">
                <!-- Dummy element to force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex flex-col flex-grow border-r border-gray-200 pt-5 pb-4 bg-white overflow-y-auto">
                <div class="mt-5 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 bg-white space-y-1">

                        <a href="/{{request()->segment(1)}}" class="bg-gray-100 text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="text-gray-500 mr-3 h-6 w-6" x-description="Heroicon name: outline/home" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>

                        <div x-data="{ isExpanded: true }" class="space-y-1">
                            <button class="group w-full flex items-center pl-2 pr-1 py-2 text-sm font-medium rounded-md bg-white text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click.prevent="isExpanded = !isExpanded" x-bind:aria-expanded="isExpanded">
                                <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6" x-description="Heroicon name: outline/folder" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                Scripts
                                <svg :class="{ 'text-gray-400 rotate-90': isExpanded, 'text-gray-300': !isExpanded }" x-state:on="Expanded" x-state:off="Collapsed" class="ml-auto h-5 w-5 transform group-hover:text-gray-400 transition-colors ease-in-out duration-150" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M6 6L14 10L6 14V6Z" fill="currentColor"></path>
                                </svg>
                            </button>
                            <div x-show="isExpanded" x-description="Expandable link section, show/hide based on state." class="space-y-1">

                                @include('scripts::_menu')

                            </div>
                        </div>

                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
            <button @click.stop="sidebarOpen = true" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" x-description="Heroicon name: outline/menu-alt-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
            </button>
            <div class="flex-1 px-4 flex justify-between">
                <div class="flex-1 flex"></div>
            </div>
        </div>

        <main class="flex-1 relative overflow-y-auto focus:outline-none" tabindex="0" x-data="" x-init="$el.focus()">
            @yield('main')
        </main>
    </div>
</div>
</body>
</html>

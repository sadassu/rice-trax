<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-emerald-50">
        <header
            class="backdrop-blur-sm bg-white/80 border-b border-gray-200/50 sticky top-0 z-50"
        >
            <nav class="max-w-6xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo/Brand area -->
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg flex items-center justify-center"
                        >
                            <img :src="'storage/assets/img/logo.jpg'" alt="" />
                        </div>
                        <span class="text-xl font-semibold text-gray-900"
                            >Rice Trax</span
                        >
                    </div>

                    <!-- Navigation links -->
                    <div class="flex items-center space-x-1">
                        <Link
                            :href="route('home')"
                            :class="[
                                'relative px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 ease-out',
                                'hover:bg-gray-100 hover:scale-105 hover:shadow-sm',
                                'active:scale-95',
                                $page.component === 'Home'
                                    ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-500/25'
                                    : 'text-gray-700 hover:text-gray-900',
                            ]"
                        >
                            <span class="relative z-10">Home</span>
                            <div
                                v-if="$page.component === 'Home'"
                                class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full animate-pulse opacity-20"
                            ></div>
                        </Link>

                        <Link
                            :href="route('login')"
                            :class="[
                                'relative px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 ease-out',
                                'hover:bg-gray-100 hover:scale-105 hover:shadow-sm',
                                'active:scale-95',
                                $page.component === 'Login'
                                    ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-500/25'
                                    : 'text-gray-700 hover:text-gray-900',
                            ]"
                        >
                            <span class="relative z-10">Login</span>
                            <div
                                v-if="$page.component === 'Login'"
                                class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full animate-pulse opacity-20"
                            ></div>
                        </Link>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <!-- Content container with subtle animation -->
            <div class="animate-fadeIn">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
}

/* Custom hover effects */
.nav-link {
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    transition: left 0.5s;
}

.nav-link:hover::before {
    left: 100%;
}
</style>

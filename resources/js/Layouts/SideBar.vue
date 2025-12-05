<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import FlashMessage from "../Components/FlashMessage.vue";
import Timer from "./Timer.vue";
import WelcomeModal from "../Components/WelcomeModal.vue";

const page = usePage();

const navItems = [
    {
        href: "admin.dashboard",
        pageComponent: "Dashboard/AdminDashboard",
        label: "Dashboard",
        icon: "/assets/icons/dashboard.svg",
        role: "admin",
    },
    {
        href: "pos.index",
        pageComponent: "Pos/Pos",
        label: "POS",
        icon: "/assets/icons/cart.svg",
    },
    {
        href: "products.index",
        pageComponent: "Products/ShowProducts",
        label: "Products",
        icon: "/assets/icons/products.svg",
    },
    {
        href: "product-batches.index",
        pageComponent: "ProductBatches/ProductBatches",
        label: "Products Batches",
        icon: "/assets/icons/products.svg",
    },
    {
        href: "sales.index",
        pageComponent: "Sales/Sales",
        label: "Sales",
        icon: "/assets/icons/sale.svg",
    },
    {
        href: "employees.index",
        pageComponent: "Employees/Employees",
        label: "Employee",
        icon: "/assets/icons/accounts.svg",
        role: "admin",
    },
    {
        href: "attendances.create",
        pageComponent: "Attendances/CreateAttendance",
        label: "Attendance Form",
        icon: "/assets/icons/calendar.svg",
    },
    {
        href: "attendances.index",
        pageComponent: "Attendances/Attendances",
        label: "Attendance",
        icon: "/assets/icons/calendar.svg",
        role: "admin",
    },
    {
        href: "accounts",
        pageComponent: "Auth/Accounts",
        label: "Accounts",
        icon: "/assets/icons/key.svg",
        role: "admin",
    },
    {
        href: "activity-logs.index",
        pageComponent: "ActivityLogs/Activity",
        label: "Activity Logs",
        icon: "/assets/icons/clock.svg",
        role: "admin",
    },
];

// Track sidebar expanded state
const expanded = ref(true);
function toggleSidebar() {
    expanded.value = !expanded.value;
}

// User menu state
const showUserMenu = ref(false);
function toggleUserMenu() {
    showUserMenu.value = !showUserMenu.value;
}

// Close menu when clicking outside
function handleClickOutside(event) {
    if (!event.target.closest(".user-menu-wrapper")) {
        showUserMenu.value = false;
    }
}
document.addEventListener("click", handleClickOutside);

const currentTime = ref("");

// Filter nav items based on user role
const filteredNavItems = computed(() => {
    return navItems.filter(
        (item) => !item.role || item.role === page.props.auth.user.role
    );
});
</script>

<template>
    <WelcomeModal />
    <FlashMessage />
    <div class="flex min-h-screen text-white">
        <!-- Desktop Sidebar -->
        <aside
            :class="`${
                expanded ? 'w-72' : 'w-20'
            } fixed left-0 top-0 h-screen z-40 bg-gradient-to-b from-lime-500 via-lime-800 to-lime-900 shadow-2xl transition-all duration-300 ease-in-out flex-col hidden md:flex`"
        >
            <!-- Header with Logo and Toggle Button -->
            <div
                class="px-6 py-6 flex justify-between items-center border-b border-lime-500/30 flex-shrink-0"
            >
                <div
                    :class="`overflow-hidden transition-all duration-300 ease-in-out ${
                        expanded ? 'w-36 opacity-100' : 'w-0 opacity-0'
                    }`"
                >
                    <div class="relative">
                        <img
                            :src="'/assets/img/logo.jpg'"
                            alt="Company Logo"
                            class="rounded-xl w-full shadow-lg ring-2 ring-white/20"
                        />
                        <div
                            class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/20 to-transparent"
                        ></div>
                    </div>
                </div>
                <button
                    @click="toggleSidebar"
                    class="p-2 rounded-xl bg-lime-500/30 hover:bg-lime-500/50 transition-all duration-200 backdrop-blur-sm border border-white/10 hover:border-white/20 group"
                    :title="expanded ? 'Collapse sidebar' : 'Expand sidebar'"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        :class="`w-5 h-5 transition-transform duration-300 ${
                            expanded ? 'rotate-0' : 'rotate-180'
                        }`"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5"
                        />
                    </svg>
                </button>
            </div>

            <!-- Navigation - Made scrollable -->
            <nav
                class="px-4 py-6 flex-1 space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-lime-500/50 scrollbar-track-transparent"
            >
                <template v-for="item in filteredNavItems" :key="item.label">
                    <div class="relative group">
                        <div class="relative">
                            <Link
                                :href="route(item.href)"
                                :class="[
                                    'flex w-full items-center px-4 py-3 rounded-xl font-medium transition-all duration-300 ease-out group/link relative overflow-hidden',
                                    'hover:bg-white/10 hover:shadow-lg hover:translate-x-1 hover:scale-105',
                                    'active:scale-95',
                                    $page.component == item.pageComponent
                                        ? 'bg-gradient-to-r from-white/25 to-white/15 text-white shadow-lg transform translate-x-1 border border-white/30 ring-2 ring-white/10'
                                        : 'text-white/90 hover:text-white border border-transparent hover:border-white/10',
                                ]"
                            >
                                <!-- Active background pulse effect -->
                                <div
                                    v-if="$page.component == item.pageComponent"
                                    class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover/link:opacity-100 transition-opacity duration-300"
                                ></div>

                                <!-- Icon Container -->
                                <div
                                    :class="`w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 relative z-10`"
                                >
                                    <img
                                        :src="item.icon"
                                        :alt="item.label"
                                        :class="`w-10 h-10 filter brightness-0 invert transition-transform duration-200 ${
                                            $page.component ==
                                            item.pageComponent
                                                ? 'scale-150'
                                                : 'group-hover/link:scale-105'
                                        }`"
                                    />
                                </div>

                                <!-- Label -->
                                <span
                                    :class="`overflow-hidden transition-all duration-300 ease-in-out whitespace-nowrap relative z-10 ${
                                        expanded
                                            ? 'w-40 ml-4 opacity-100'
                                            : 'w-0 ml-0 opacity-0'
                                    } ${
                                        $page.component == item.pageComponent
                                            ? 'font-semibold'
                                            : 'font-medium'
                                    }`"
                                >
                                    {{ item.label }}
                                </span>

                                <!-- Enhanced active indicator -->
                                <div
                                    :class="`absolute left-0 w-1 h-8 bg-gradient-to-b from-white to-white/80 rounded-r-full transition-all duration-300 ${
                                        $page.component == item.pageComponent
                                            ? 'opacity-100 shadow-lg shadow-white/25'
                                            : 'opacity-0 group-hover/link:opacity-60'
                                    }`"
                                ></div>

                                <!-- Subtle glow effect for active items -->
                                <div
                                    v-if="$page.component == item.pageComponent"
                                    class="absolute inset-0 rounded-xl bg-gradient-to-r from-white/5 to-transparent pointer-events-none"
                                ></div>
                            </Link>

                            <div
                                v-if="!expanded"
                                class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-xl invisible opacity-0 -translate-x-2 transition-all duration-200 group-hover:visible group-hover:opacity-100 group-hover:translate-x-0 whitespace-nowrap z-50 border border-gray-700"
                            >
                                {{ item.label }}
                                <div
                                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1 w-2 h-2 bg-gray-900 rotate-45 border-l border-b border-gray-700"
                                ></div>
                            </div>
                        </div>
                    </div>
                </template>
            </nav>

            <!-- User section at bottom -->
            <div
                class="border-t border-lime-500/30 p-4 mt-auto relative user-menu-wrapper flex-shrink-0"
            >
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-lime-300 to-lime-500 flex items-center justify-center text-lime-900 font-bold text-lg shadow-lg ring-2 ring-white/20"
                        >
                            {{
                                $page.props.auth.user.name
                                    .split(" ")
                                    .map((n) => n[0])
                                    .join("")
                            }}
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-lime-700 animate-pulse"
                        ></div>
                    </div>

                    <div
                        :class="`flex justify-between items-center overflow-hidden transition-all duration-300 ease-in-out ${
                            expanded ? 'w-44 opacity-100' : 'w-0 opacity-0'
                        }`"
                    >
                        <div class="min-w-0 flex-1">
                            <h4
                                class="font-semibold text-white truncate text-sm"
                            >
                                {{ $page.props.auth.user.name }}
                            </h4>
                            <p class="text-xs text-lime-200 truncate">
                                {{ $page.props.auth.user.email }}
                            </p>
                        </div>

                        <button
                            @click.stop="toggleUserMenu"
                            :class="`p-2 rounded-lg transition-all duration-200 ml-2 ${
                                showUserMenu
                                    ? 'bg-white/20 text-white transform scale-110'
                                    : 'hover:bg-white/10 text-lime-200 hover:text-white'
                            }`"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                :class="`w-4 h-4 transition-transform duration-200 ${
                                    showUserMenu ? 'rotate-90' : ''
                                }`"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"
                                />
                            </svg>
                        </button>

                        <!-- User Menu Dropdown -->
                        <div
                            v-if="showUserMenu"
                            class="absolute right-4 bottom-16 bg-white text-gray-800 shadow-xl rounded-xl w-40 py-2 z-50 border border-gray-100 animate-in slide-in-from-bottom-2 duration-200"
                        >
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                type="button"
                                class="flex items-center w-full text-left px-4 py-2 hover:bg-red-50 transition-colors duration-150 text-red-600 hover:text-red-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 mr-2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                                    />
                                </svg>
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Bottom Navigation - Made scrollable -->
        <nav
            class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-gradient-to-r from-lime-600 via-lime-700 to-lime-800 shadow-2xl border-t border-lime-500/30"
        >
            <div
                class="flex items-center py-2 overflow-x-auto scrollbar-thin scrollbar-thumb-lime-500/50 scrollbar-track-transparent px-2"
            >
                <template v-for="item in filteredNavItems" :key="item.label">
                    <Link
                        :href="route(item.href)"
                        :class="[
                            'flex flex-col items-center justify-center py-3 px-4 rounded-xl transition-all duration-300 ease-out min-w-0 relative flex-shrink-0',
                            'hover:bg-white/10 hover:scale-105 hover:shadow-sm',
                            'active:scale-95',
                            $page.component == item.pageComponent
                                ? 'bg-gradient-to-r from-white/25 to-white/15 text-white transform scale-110 shadow-lg'
                                : 'text-white/80 hover:text-white',
                        ]"
                    >
                        <div
                            :class="[
                                'w-6 h-6 flex items-center justify-center rounded-lg transition-all duration-200 mb-1',
                            ]"
                        >
                            <img
                                :src="item.icon"
                                :alt="item.label"
                                class="w-5 h-5 filter brightness-0 invert"
                            />
                        </div>
                        <span
                            :class="[
                                'text-xs font-medium transition-all duration-200 whitespace-nowrap',
                                $page.component == item.pageComponent
                                    ? 'block font-semibold'
                                    : 'hidden xs:block',
                            ]"
                        >
                            {{ item.label }}
                        </span>
                    </Link>
                </template>

                <!-- User Menu in Mobile -->
                <div class="relative user-menu-wrapper flex-shrink-0">
                    <button
                        @click.stop="toggleUserMenu"
                        :class="`flex flex-col items-center justify-center py-3 px-4 rounded-xl transition-all duration-200 ${
                            showUserMenu
                                ? 'bg-white/25 text-white transform scale-110 shadow-lg'
                                : 'text-white/80 hover:text-white hover:bg-white/10'
                        }`"
                    >
                        <div
                            :class="`w-6 h-6 rounded-full bg-gradient-to-br from-lime-300 to-lime-500 flex items-center justify-center text-lime-900 font-bold text-xs mb-1 transition-all duration-200 ${
                                showUserMenu ? 'shadow-md scale-110' : ''
                            }`"
                        >
                            {{
                                $page.props.auth.user.name
                                    .split(" ")
                                    .map((n) => n[0])
                                    .join("")
                            }}
                        </div>
                        <span
                            :class="`text-xs font-medium transition-all duration-200 whitespace-nowrap ${
                                showUserMenu
                                    ? 'hidden xs:block font-semibold'
                                    : 'hidden xs:block'
                            }`"
                        >
                            Profile
                        </span>
                    </button>

                    <!-- Mobile User Menu Dropdown -->
                    <div
                        v-if="showUserMenu"
                        class="fixed inset-0 z-50 flex items-end justify-center bg-black/30"
                        @click.self="showUserMenu = false"
                    >
                        <div
                            class="w-full max-w-sm bg-white text-gray-800 shadow-xl rounded-t-2xl py-4 border-t border-gray-200 animate-in slide-in-from-bottom-2 duration-200"
                        >
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-semibold text-sm truncate">
                                    {{ $page.props.auth.user.name }}
                                </p>
                                <p class="text-xs text-gray-600 truncate">
                                    {{ $page.props.auth.user.email }}
                                </p>
                            </div>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                type="button"
                                class="flex items-center w-full text-left px-4 py-3 hover:bg-red-50 transition-colors duration-150 text-red-600 hover:text-red-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 mr-2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                                    />
                                </svg>
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main
            :class="`flex-1 bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 transition-all duration-300 ease-in-out min-h-screen ${
                expanded ? 'md:ml-72' : 'md:ml-20'
            } pb-20 md:pb-0 mb-4 sm:mb-6 md:mb-0`"
        >
            <div
                class="sticky top-0 z-30 bg-white/80 backdrop-blur-sm border-b border-gray-200/50"
            >
                <Timer />
            </div>
            <div class="mt-10">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
@keyframes slide-in-from-bottom-2 {
    from {
        transform: translateY(8px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-in {
    animation-duration: 200ms;
    animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    animation-fill-mode: both;
}

.slide-in-from-bottom-2 {
    animation-name: slide-in-from-bottom-2;
}

/* Custom breakpoint for very small screens */
@media (min-width: 475px) {
    .xs\:block {
        display: block;
    }
}

/* Additional smooth animations */
@keyframes pulse-glow {
    0%,
    100% {
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
}

.animate-pulse-glow {
    animation: pulse-glow 2s infinite;
}

/* Custom scrollbar styles */
.scrollbar-thin {
    scrollbar-width: thin;
}

.scrollbar-thumb-lime-500\/50::-webkit-scrollbar-thumb {
    background-color: rgba(20, 184, 166, 0.5);
    border-radius: 0.375rem;
}

.scrollbar-track-transparent::-webkit-scrollbar-track {
    background-color: transparent;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    border-radius: 0.375rem;
    background-color: rgba(20, 184, 166, 0.5);
}

.scrollbar-thin::-webkit-scrollbar-track {
    background-color: transparent;
}

/* Hide scrollbar for Firefox */
.scrollbar-thin {
    scrollbar-color: rgba(20, 184, 166, 0.5) transparent;
}
</style>

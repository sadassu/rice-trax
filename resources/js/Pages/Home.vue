<script setup>
const props = defineProps({
    products: Number,
    sale: Number,
});
</script>

<template>
    <Head
        :title="`${$page.component} | Rich-Anne Store POS System`"
    />

    <div
        class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50"
    >
        <!-- Hero Section with Brand Identity -->
        <section
            class="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600 text-white"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute inset-0"
                    style="
                        background-image: url('data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 100 100&quot;><circle cx=&quot;20&quot; cy=&quot;20&quot; r=&quot;2&quot; fill=&quot;white&quot;/><circle cx=&quot;80&quot; cy=&quot;80&quot; r=&quot;2&quot; fill=&quot;white&quot;/><circle cx=&quot;40&quot; cy=&quot;60&quot; r=&quot;1&quot; fill=&quot;white&quot;/></svg>');
                        background-size: 50px 50px;
                    "
                ></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-6 py-16">
                <div class="text-center space-y-6">
                    <!-- Logo and Brand -->
                    <div
                        class="flex items-center justify-center space-x-4 mb-8"
                    >
                        <div class="relative">
                            <div
                                class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30"
                            >
                                <!-- Rice grain icon -->
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 2C10.9 2 10 2.9 10 4V6.17C8.83 6.58 8 7.69 8 9V20C8 21.1 8.9 22 10 22H14C15.1 22 16 21.1 16 20V9C16 7.69 15.17 6.58 14 6.17V4C14 2.9 13.1 2 12 2ZM12 4V6H12C13.1 6 14 6.9 14 8V20H10V8C10 6.9 10.9 6 12 6V4Z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="absolute -top-1 -right-1 w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center"
                            >
                                <span class="text-xs font-bold text-emerald-800"
                                    >R</span
                                >
                            </div>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold tracking-tight">
                                RiceTrax
                            </h1>
                            <p class="text-emerald-100 text-lg">
                                Rich-Anne Store
                            </p>
                        </div>
                    </div>

                    <!-- Welcome Message -->
                    <div class="max-w-3xl mx-auto">
                        <h2 class="text-3xl font-semibold mb-4">
                            Point of Sale System
                        </h2>
                        <p class="text-xl text-emerald-100 leading-relaxed">
                            Professional rice retail management solution for
                            inventory tracking, sales processing, and business
                            analytics
                        </p>
                    </div>

                    <!-- Feature Highlights -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300"
                        >
                            <div
                                class="w-12 h-12 bg-amber-400 rounded-lg flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-emerald-800"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">
                                Inventory Management
                            </h3>
                            <p class="text-sm text-emerald-100">
                                Track rice varieties, quantities, and supplier
                                information
                            </p>
                        </div>

                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300"
                        >
                            <div
                                class="w-12 h-12 bg-amber-400 rounded-lg flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-emerald-800"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">
                                Sales Processing
                            </h3>
                            <p class="text-sm text-emerald-100">
                                Fast and accurate transaction processing with
                                receipts
                            </p>
                        </div>

                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300"
                        >
                            <div
                                class="w-12 h-12 bg-amber-400 rounded-lg flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-emerald-800"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 00-2 2h2a2 2 0 002-2V9a2 2 0 00-2-2H9a2 2 0 00-2 2v10"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">
                                Business Analytics
                            </h3>
                            <p class="text-sm text-emerald-100">
                                Detailed reports and insights for better
                                decision making
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Area -->
        <section class="max-w-7xl mx-auto px-6 py-12">
            <div
                class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden"
            >
                <div
                    class="bg-gradient-to-r from-gray-50 to-emerald-50 px-8 py-6 border-b border-gray-200"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900">
                                Welcome to RiceTrax POS
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Manage your rice retail business with confidence
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"
                            ></div>
                            <span class="text-sm text-gray-500"
                                >System Online</span
                            >
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Quick Stats -->
                        <div class="space-y-6">
                            <h4
                                class="text-lg font-semibold text-gray-900 flex items-center"
                            >
                                <span
                                    class="w-2 h-2 bg-emerald-500 rounded-full mr-3"
                                ></span>
                                Store Overview
                            </h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-emerald-50 rounded-lg p-4 border border-emerald-200"
                                >
                                    <div
                                        class="text-2xl font-bold text-emerald-700"
                                    >
                                        {{ props.products }}
                                    </div>
                                    <div class="text-sm text-emerald-600">
                                        Rice Varieties
                                    </div>
                                </div>
                                <div
                                    class="bg-teal-50 rounded-lg p-4 border border-teal-200"
                                >
                                    <div
                                        class="text-2xl font-bold text-teal-700"
                                    >
                                        ₱{{ props.sale }}
                                    </div>
                                    <div class="text-sm text-teal-600">
                                        Total Sales
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="space-y-6">
                            <h4
                                class="text-lg font-semibold text-gray-900 flex items-center"
                            >
                                <span
                                    class="w-2 h-2 bg-teal-500 rounded-full mr-3"
                                ></span>
                                System Status
                            </h4>

                            <div class="space-y-3">
                                <div
                                    class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200"
                                >
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >Database Connection</span
                                    >
                                    <span
                                        class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full"
                                        >Active</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

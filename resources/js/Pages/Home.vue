<script setup>
const props = defineProps({
    products: Number,
    sale: Number,
});
</script>

<template>
    <Head :title="`${$page.component} | Rich-Anne Store POS System`" />

    <div
        class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-lime-50"
    >
        <!-- Hero Section with Brand Identity -->
        <section
            class="relative overflow-hidden bg-[url(/assets/img/background-rt.jpg)] bg-no-repeat bg-cover text-white h-1/2"
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

            <div class="relative max-w-7xl mx-auto px-6 py-72">
                <div class="text-center space-y-6 p-5 bg-black/50 rounded-xl">
                    <!-- Logo and Brand -->
                    <div
                        class="flex items-center justify-center space-x-4 mb-8"
                    >
                        <div class="relative">
                            <div
                                class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30"
                            >
                                <!-- Rice grain icon -->
                                <img
                                    :src="'/assets/img/logo.jpg'"
                                    alt=""
                                    class="rounded-2xl"
                                />
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
                                RiceTraX
                            </h1>
                            <p class="text-emerald-100 text-lg">
                                Rich-Anne Store
                            </p>
                        </div>
                    </div>

                    <!-- Welcome Message -->
                    <div class="max-w-3xl mx-auto">
                        <h2 class="text-3xl font-semibold mb-4">
                            Sales and Inventory System
                        </h2>
                        <p class="text-xl text-emerald-100 leading-relaxed">
                            Professional rice retail management solution for
                            inventory tracking, sales processing, and business
                            analytics
                        </p>
                    </div>

                    <!-- Feature Highlights -->
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div
                    class="bg-white rounded-xl p-6 border border-emerald-200 hover:shadow-lg hover:border-emerald-300 transition-all duration-300"
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
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">
                        Inventory Management
                    </h3>
                    <p class="text-sm text-gray-600">
                        Track rice varieties, quantities, and supplier
                        information
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl p-6 border border-emerald-200 hover:shadow-lg hover:border-emerald-300 transition-all duration-300"
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
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">
                        Sales Processing
                    </h3>
                    <p class="text-sm text-gray-600">
                        Fast and accurate transaction processing with receipts
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl p-6 border border-emerald-200 hover:shadow-lg hover:border-emerald-300 transition-all duration-300"
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
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">
                        Business Analytics
                    </h3>
                    <p class="text-sm text-gray-600">
                        Detailed reports and insights for better decision making
                    </p>
                </div>
            </div>
        </section>
    </div>
</template>

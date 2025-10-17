<script setup>
import { formatCurrency } from "../../utils/currencyFormat";

defineProps({
    totals: Object,
});
</script>
<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Daily Sales Card -->
        <div
            class="bg-gradient-to-r from-lime-500 to-lime-600 rounded-2xl p-6 text-white shadow-lg"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-lime-100 text-sm font-medium uppercase tracking-wide"
                    >
                        Daily Sales
                    </p>
                    <p class="text-3xl font-bold mt-2">
                        {{ formatCurrency(totals?.today || 0) }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                        ></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Weekly Sales Card -->
        <div
            class="bg-gradient-to-r from-lime-500 to-lime-600 rounded-2xl p-6 text-white shadow-lg"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-blue-100 text-sm font-medium uppercase tracking-wide"
                    >
                        Weekly Sales
                    </p>
                    <p class="text-3xl font-bold mt-2">
                        {{ formatCurrency(totals?.week || 0) }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                        ></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Card -->
        <div
            class="bg-gradient-to-r from-lime-500 to-lime-600 rounded-2xl p-6 text-white shadow-lg"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-purple-100 text-sm font-medium uppercase tracking-wide"
                    >
                        Monthly Sales
                    </p>
                    <p class="text-3xl font-bold mt-2">
                        {{ formatCurrency(totals?.month || 0) }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                        ></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

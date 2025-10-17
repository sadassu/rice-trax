<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { Link, router } from "@inertiajs/vue3";
import { watch } from "vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import ExportSale from "./ExportSale.vue";
import ImportSale from "./ImportSale.vue";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    sale: Object,
    filters: Object,
    years: Array,
    months: Object,
    totals: Object,
});

const filter = (event) => {
    router.get(
        route("sales.index"),
        {
            month:
                event.target.name === "month"
                    ? event.target.value
                    : props.filters.month,
            year:
                event.target.name === "year"
                    ? event.target.value
                    : props.filters.year,
            date:
                event.target.name === "date"
                    ? event.target.value
                    : props.filters.date,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const clearFilters = () => {
    router.get(route("sales.index"));
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
    }).format(amount);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-PH", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-3 sm:p-6"
    >
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8">
            <!-- Header Section -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-0"
                    >
                        Sales
                    </h1>
                    <p class="text-gray-600">
                        Track and manage your sales performance
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards with Modern Gradient Design -->
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8"
        >
            <div
                class="relative bg-gradient-to-r from-lime-600 to-lime-700 text-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 overflow-hidden"
            >
                <div
                    class="absolute top-0 right-0 w-20 h-20 sm:w-24 sm:h-24 bg-white/10 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12"
                ></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-xs sm:text-sm font-medium opacity-90">
                            Today's Sales
                        </h3>
                        <div
                            class="w-7 h-7 sm:w-8 sm:h-8 bg-white/20 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-3.5 h-3.5 sm:w-4 sm:h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                                />
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold">
                        {{ formatCurrency(props.totals.today) }}
                    </p>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-r from-lime-600 to-lime-700 text-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 overflow-hidden"
            >
                <div
                    class="absolute top-0 right-0 w-20 h-20 sm:w-24 sm:h-24 bg-white/10 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12"
                ></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-xs sm:text-sm font-medium opacity-90">
                            This Week
                        </h3>
                        <div
                            class="w-7 h-7 sm:w-8 sm:h-8 bg-white/20 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-3.5 h-3.5 sm:w-4 sm:h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold">
                        {{ formatCurrency(props.totals.week) }}
                    </p>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-r from-lime-600 to-lime-700 text-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 overflow-hidden sm:col-span-2 lg:col-span-1"
            >
                <div
                    class="absolute top-0 right-0 w-20 h-20 sm:w-24 sm:h-24 bg-white/10 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12"
                ></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-xs sm:text-sm font-medium opacity-90">
                            This Month
                        </h3>
                        <div
                            class="w-7 h-7 sm:w-8 sm:h-8 bg-white/20 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-3.5 h-3.5 sm:w-4 sm:h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold">
                        {{ formatCurrency(props.totals.month) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200/50"
        >
            <!-- Filters Section -->
            <div class="p-4 sm:p-6 border-b border-gray-200/50">
                <h2
                    class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6"
                >
                    Sales History
                </h2>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4"
                >
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Month</label
                        >
                        <select
                            name="month"
                            class="w-full rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 text-sm"
                            :value="filters.month"
                            @change="filter"
                        >
                            <option value="">All Months</option>
                            <option
                                v-for="(monthName, monthNum) in months"
                                :key="monthNum"
                                :value="monthNum"
                            >
                                {{ monthName }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Year</label
                        >
                        <select
                            name="year"
                            class="w-full rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 text-sm"
                            :value="filters.year"
                            @change="filter"
                        >
                            <option value="">All Years</option>
                            <option
                                v-for="year in years"
                                :key="year"
                                :value="year"
                            >
                                {{ year }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Date</label
                        >
                        <input
                            type="date"
                            name="date"
                            class="w-full rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 text-sm"
                            :value="filters.date"
                            @change="filter"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            @click="clearFilters"
                            class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-100 text-gray-700 rounded-lg sm:rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium text-sm"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <ExportSale />
                    <ImportSale />
                </div>
            </div>

            <!-- Desktop Table View - Hidden on Mobile -->
            <div class="hidden md:block overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Sale Date
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Total Price
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Amount Paid
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Change
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            <tr
                                v-for="item in sale.data"
                                :key="item.id"
                                class="hover:bg-gray-50/50 transition-colors duration-150"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ formatDate(item.sale_date) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"
                                >
                                    {{ formatCurrency(item.total_price) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{ formatCurrency(item.amount_paid) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                                >
                                    {{ formatCurrency(item.change) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                >
                                    <button
                                        @click="
                                            router.get(
                                                route('receipt', item.id)
                                            )
                                        "
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        View Receipt
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View - Hidden on Desktop -->
            <div class="md:hidden divide-y divide-gray-200/50">
                <div
                    v-for="item in sale.data"
                    :key="item.id"
                    class="p-4 hover:bg-gray-50/50 transition-colors duration-150"
                >
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <div
                                    class="w-2 h-2 bg-blue-500 rounded-full"
                                ></div>
                                <span
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wide"
                                >
                                    Sale Date
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ formatDate(item.sale_date) }}
                            </p>
                        </div>
                        <button
                            @click="router.get(route('receipt', item.id))"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            <svg
                                class="w-3 h-3 mr-1.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>
                            View
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 rounded-lg border border-green-100"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg
                                            class="w-3 h-3 text-green-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                                            />
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <span
                                            class="text-xs font-medium text-green-700"
                                            >Total Price</span
                                        >
                                    </div>
                                    <p class="text-lg font-bold text-green-900">
                                        {{ formatCurrency(item.total_price) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg
                                        class="w-3 h-3 text-gray-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        />
                                    </svg>
                                    <span
                                        class="text-xs font-medium text-gray-600"
                                        >Amount Paid</span
                                    >
                                </div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ formatCurrency(item.amount_paid) }}
                                </p>
                            </div>

                            <div class="bg-orange-50 p-3 rounded-lg">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg
                                        class="w-3 h-3 text-orange-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span
                                        class="text-xs font-medium text-orange-600"
                                        >Change</span
                                    >
                                </div>
                                <p
                                    class="text-sm font-semibold text-orange-900"
                                >
                                    {{ formatCurrency(item.change) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div
                v-if="sale.data.length === 0"
                class="text-center py-12 sm:py-16"
            >
                <div
                    class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <svg
                        class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    No sales found
                </h3>
                <p class="text-gray-500 text-sm sm:text-base">
                    Try adjusting your filters to see more results.
                </p>
            </div>

            <!-- Modern Pagination -->
            <PaginationLinks
                :links="sale.links"
                :from="sale.from"
                :to="sale.to"
                :total="sale.total"
            />
        </div>
    </div>
</template>

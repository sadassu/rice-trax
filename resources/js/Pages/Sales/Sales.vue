<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { router } from "@inertiajs/vue3";
import { watch } from "vue";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    sale: Object,
    filters: Object,
    years: Array,
    months: Object,
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
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Sales List</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Filters -->
            <div class="flex gap-4 mb-6 flex-wrap">
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700"
                        >Month</label
                    >
                    <select
                        name="month"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700"
                        >Year</label
                    >
                    <select
                        name="year"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :value="filters.year"
                        @change="filter"
                    >
                        <option value="">All Years</option>
                        <option v-for="year in years" :key="year" :value="year">
                            {{ year }}
                        </option>
                    </select>
                </div>

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700"
                        >Date</label
                    >
                    <input
                        type="date"
                        name="date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :value="filters.date"
                        @change="filter"
                    />
                </div>

                <div class="flex items-end">
                    <button
                        type="button"
                        @click="clearFilters"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Sales Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Sale Date
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Total Price
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Amount Paid
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Change
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="item in sale.data"
                            :key="item.id"
                            class="hover:bg-gray-50"
                        >
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                {{ formatDate(item.sale_date) }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            >
                                {{ formatCurrency(item.total_price) }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            >
                                {{ formatCurrency(item.amount_paid) }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            >
                                {{ formatCurrency(item.change) }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            >
                                <div class="flex space-x-2">
                                    <button
                                        @click="
                                            router.get(
                                                route('receipt', item.id)
                                            )
                                        "
                                        class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded bg-indigo-100 hover:bg-indigo-200"
                                    >
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty state -->
                <div v-if="sale.data.length === 0" class="text-center py-8">
                    <p class="text-gray-500">No sales found.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6" v-if="sale.links && sale.links.length > 3">
                <div class="flex justify-center">
                    <nav class="flex space-x-1">
                        <template v-for="(link, key) in sale.links" :key="key">
                            <button
                                v-if="link.url"
                                @click="router.get(link.url)"
                                class="px-3 py-2 text-sm rounded-md border"
                                :class="{
                                    'bg-indigo-500 text-white border-indigo-500':
                                        link.active,
                                    'text-gray-500 border-gray-300 hover:bg-gray-50':
                                        !link.active,
                                }"
                                v-html="link.label"
                            ></button>
                            <span
                                v-else
                                class="px-3 py-2 text-sm text-gray-400 border border-gray-300 rounded-md"
                                v-html="link.label"
                            ></span>
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

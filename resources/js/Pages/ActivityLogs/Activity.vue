<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { Link, router } from "@inertiajs/vue3";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    logs: Object,
    filters: Object,
    years: Array,
    months: Object,
});

const filter = (event) => {
    const newFilters = {
        user_id: props.filters.user_id,
        module: props.filters.module,
        event: props.filters.event,
        date: props.filters.date,
        month: props.filters.month,
        year: props.filters.year,
    };

    newFilters[event.target.name] = event.target.value;

    router.get(route("activity-logs.index"), newFilters, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    router.get(route("activity-logs.index"));
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString("en-PH", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Activity Logs</h1>
            <p class="text-gray-600">Track user activity across the system</p>
        </div>

        <!-- Filters -->
        <div
            class="bg-white rounded-2xl shadow-lg border border-gray-200/50 mb-6"
        >
            <div class="p-6 border-b border-gray-200/50">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">
                    Filters
                </h2>

                <div class="flex gap-4 flex-wrap">
                    <div class="min-w-48">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Month</label
                        >
                        <select
                            name="month"
                            :value="filters.month"
                            @change="filter"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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

                    <div class="min-w-48">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Year</label
                        >
                        <select
                            name="year"
                            :value="filters.year"
                            @change="filter"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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

                    <div class="min-w-48">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Date</label
                        >
                        <input
                            type="date"
                            name="date"
                            :value="filters.date"
                            @change="filter"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            @click="clearFilters"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Table (desktop) -->
        <div
            class="bg-white rounded-2xl shadow-lg border border-gray-200/50 hidden md:block"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                User
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Event
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Module
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Description
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50">
                        <tr
                            v-for="log in logs.data"
                            :key="log.id"
                            class="hover:bg-gray-50/50"
                        >
                            <td
                                class="px-6 py-4 text-sm font-medium text-gray-900"
                            >
                                {{ log.user?.name || "System" }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ log.event }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ log.module }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ log.description }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ formatDate(log.created_at) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Logs Cards (mobile) -->
        <div class="grid gap-4 md:hidden">
            <div
                v-for="log in logs.data"
                :key="log.id"
                class="bg-white shadow-md rounded-xl border border-gray-200 p-4"
            >
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-900">
                        {{ log.user?.name || "System" }}
                    </h3>
                    <span
                        class="text-xs px-2 py-1 rounded-lg bg-blue-100 text-blue-700 font-medium"
                    >
                        {{ log.event }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">Module:</span> {{ log.module }}
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">Description:</span>
                    {{ log.description }}
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    {{ formatDate(log.created_at) }}
                </p>
            </div>

            <!-- Empty State (mobile) -->
            <div
                v-if="logs.data.length === 0"
                class="text-center py-12 bg-white rounded-xl shadow-md"
            >
                <div
                    class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <svg
                        class="w-10 h-10 text-gray-400"
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
                    No activity logs found
                </h3>
                <p class="text-gray-500">
                    Try adjusting your filters to see more results.
                </p>
            </div>
        </div>

        <!-- Pagination -->
        <div
            v-if="logs.links && logs.links.length > 3"
            class="bg-gray-50 px-6 py-3 border border-gray-200 mt-4 rounded-2xl"
        >
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="text-sm text-gray-700">
                    {{ logs.from }}-{{ logs.to }} of {{ logs.total }} results
                </div>
                <div class="flex gap-1 flex-wrap">
                    <Link
                        v-for="link in logs.links"
                        :key="link.label"
                        :href="link.url || ''"
                        :preserve-scroll="true"
                        v-html="link.label"
                        class="px-3 py-1 text-sm rounded-md transition-all duration-200"
                        :class="{
                            'text-gray-400 cursor-not-allowed': !link.url,
                            'bg-gray-900 text-white': link.active,
                            'text-gray-700 hover:bg-gray-200 hover:scale-105':
                                link.url && !link.active,
                        }"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

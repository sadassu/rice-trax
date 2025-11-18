<script setup>
import { formatDate } from "../../utils/dateFormat";

defineProps({
    notifications: Array,
});
</script>

<template>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-red-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold">Notifications</h3>
            <span
                class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
            >
                Latest {{ notifications?.length || 0 }}
            </span>
        </div>

        <!-- Table view (desktop) -->
        <div
            class="overflow-x-auto hidden md:block"
            v-if="notifications?.length"
        >
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-red-100">
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-red-500 uppercase tracking-wider"
                        >
                            Title
                        </th>
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-red-500 uppercase tracking-wider"
                        >
                            Message
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    <tr
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="hover:bg-red-50 transition-colors"
                    >
                        <td class="py-3 px-2 text-sm text-red-900 font-medium">
                            {{ notification.title }}
                        </td>
                        <td class="py-3 px-2 text-sm text-red-600">
                            {{ notification.message }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Card view (mobile) -->
        <div class="space-y-4 md:hidden" v-if="notifications?.length">
            <div
                v-for="notification in notifications"
                :key="notification.id"
                class="p-4 border border-red-200 rounded-xl shadow-sm hover:bg-red-50 transition"
            >
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-red-900">
                        {{ notification.title }}
                    </span>
                </div>
                <div class="text-sm text-red-600">
                    {{ notification.message }}
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-else class="h-64 flex items-center justify-center text-red-300">
            <div class="text-center">
                <svg
                    class="w-12 h-12 mx-auto mb-4 text-red-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405M19 13v-2a7 7 0 00-14 0v2m4 0v2m4-2v2m-8-2v2m0 0H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v6a2 2 0 01-2 2h-3"
                    ></path>
                </svg>
                <p class="text-sm text-red-400">No notifications</p>
            </div>
        </div>
    </div>
</template>

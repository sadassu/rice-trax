<script setup>
import { formatDate } from "../../utils/dateFormat";

defineProps({
    recentLogs: Object,
});
</script>

<template>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">
                Recent Activity Logs
            </h3>
            <span
                class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
            >
                Latest {{ recentLogs?.length || 0 }}
            </span>
        </div>

        <!-- ✅ Table view (desktop) -->
        <div class="overflow-x-auto hidden md:block" v-if="recentLogs?.length">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Event
                        </th>
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Module
                        </th>
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Description
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr
                        v-for="log in recentLogs"
                        :key="log.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="py-3 px-2">
                            <span
                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                :class="{
                                    'bg-green-100 text-green-800':
                                        log.event === 'LOGIN',
                                    'bg-blue-100 text-blue-800':
                                        log.event === 'CREATE',
                                    'bg-yellow-100 text-yellow-800':
                                        log.event === 'UPDATE',
                                    'bg-red-100 text-red-800':
                                        log.event === 'DELETE',
                                    'bg-gray-100 text-gray-800': ![
                                        'LOGIN',
                                        'CREATE',
                                        'UPDATE',
                                        'DELETE',
                                    ].includes(log.event),
                                }"
                            >
                                {{ log.event }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-900 font-medium">
                            {{ log.module }}
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-600">
                            {{ log.description }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ✅ Card view (mobile) -->
        <div class="space-y-4 md:hidden" v-if="recentLogs?.length">
            <div
                v-for="log in recentLogs"
                :key="log.id"
                class="p-4 border rounded-xl shadow-sm hover:bg-gray-50 transition"
            >
                <div class="flex justify-between items-center mb-2">
                    <span
                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                        :class="{
                            'bg-green-100 text-green-800':
                                log.event === 'LOGIN',
                            'bg-blue-100 text-blue-800': log.event === 'CREATE',
                            'bg-yellow-100 text-yellow-800':
                                log.event === 'UPDATE',
                            'bg-red-100 text-red-800': log.event === 'DELETE',
                            'bg-gray-100 text-gray-800': ![
                                'LOGIN',
                                'CREATE',
                                'UPDATE',
                                'DELETE',
                            ].includes(log.event),
                        }"
                    >
                        {{ log.event }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ formatDate(log.created_at) }}
                    </span>
                </div>
                <div class="text-sm font-medium text-gray-900">
                    {{ log.module }}
                </div>
                <div class="text-sm text-gray-600">
                    {{ log.description }}
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-else class="h-64 flex items-center justify-center text-gray-500">
            <div class="text-center">
                <svg
                    class="w-12 h-12 mx-auto mb-4 text-gray-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                <p class="text-sm">No recent logs</p>
            </div>
        </div>
    </div>
</template>

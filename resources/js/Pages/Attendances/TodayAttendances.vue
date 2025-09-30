<script setup>
import { formatCurrency } from "../../utils/currencyFormat";
import { formatTime } from "../../utils/helper";

defineProps({
    todayAttendances: Object,
});

const getStatusColor = (status) => {
    const colors = {
        present: "bg-green-100 text-green-800",
        late: "bg-yellow-100 text-yellow-800",
        absent: "bg-red-100 text-red-800",
        "half-day": "bg-blue-100 text-blue-800",
    };
    return colors[status] || "bg-gray-100 text-gray-800";
};
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
    >
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3"
                    >
                        <svg
                            class="w-5 h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            Today's Attendances
                        </h3>
                        <p class="text-green-100 text-sm">
                            {{ new Date().toLocaleDateString() }}
                        </p>
                    </div>
                </div>
                <span
                    class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium"
                >
                    {{ todayAttendances.length }} Records
                </span>
            </div>
        </div>

        <div class="p-6">
            <div
                v-if="todayAttendances.length"
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"
            >
                <div
                    v-for="attendance in todayAttendances"
                    :key="attendance.id"
                    class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow"
                >
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 text-green-700 font-semibold"
                            >
                                {{
                                    attendance.employee.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">
                                    {{ attendance.employee.name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ attendance.employee.role }}
                                </p>
                            </div>
                        </div>
                        <span
                            :class="[
                                'px-2 py-1 rounded-full text-xs font-medium',
                                getStatusColor(attendance.status),
                            ]"
                        >
                            {{ attendance.status }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Time In:</span>
                            <span class="font-medium">{{
                                formatTime(attendance.time_in) || "Not set"
                            }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Time Out:</span>
                            <span class="font-medium">{{
                                formatTime(attendance.time_out) || "Not set"
                            }}</span>
                        </div>
                        <div
                            v-if="attendance.earned_amount"
                            class="flex justify-between text-sm"
                        >
                            <span class="text-gray-500">Earned:</span>
                            <span class="font-medium text-green-600"
                                >{{
                                    formatCurrency(attendance.earned_amount)
                                }}</span
                            >
                        </div>
                        <div v-if="attendance.remarks" class="mt-2">
                            <p class="text-xs text-gray-500">Remarks:</p>
                            <p class="text-sm text-gray-700">
                                {{ attendance.remarks }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-8">
                <svg
                    class="mx-auto h-12 w-12 text-gray-300 mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
                <p class="text-gray-500 font-medium">
                    No attendance records for today
                </p>
                <p class="text-sm text-gray-400">
                    Start by adding an employee's attendance above
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { Link } from "@inertiajs/vue3"; // ✅ for navigation
import { formatDate } from "../../utils/dateFormat";

defineOptions({ layout: SideBar });

defineProps({
    employee: Object,
});

const goBack = () => {
    window.history.back();
};

// ✅ Helper: format time into 12-hour format with AM/PM
const formatTime = (datetime) => {
    if (!datetime) return "-";
    const date = new Date(datetime);
    const options = {
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    };
    return date.toLocaleTimeString("en-US", options);
};
</script>

<template>
    <div class="m-6 space-y-8">
        <!-- ✅ Back Button -->
        <div>
            <button
                @click="goBack"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back
            </button>
        </div>

        <!-- Employee Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                Employee Information
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Name</p>
                    <p class="font-medium text-gray-900">{{ employee.name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Contact</p>
                    <p class="font-medium text-gray-900">
                        {{ employee.contact }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Rate</p>
                    <p class="font-medium text-gray-900">
                        ₱{{ employee.rate }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Role</p>
                    <p class="font-medium text-gray-900 capitalize">
                        {{ employee.role }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Attendance Records -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">
                    Attendance Records
                </h3>
                <span
                    class="text-sm text-gray-500"
                    v-if="employee.attendances && employee.attendances.length"
                >
                    Total: {{ employee.attendances.length }}
                </span>
            </div>

            <div v-if="employee.attendances && employee.attendances.length">
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Date
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Time In
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Time Out
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr
                                v-for="attendance in employee.attendances"
                                :key="attendance.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ formatDate(attendance.date) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-700':
                                                attendance.status === 'present',
                                            'bg-red-100 text-red-700':
                                                attendance.status === 'absent',
                                            'bg-yellow-100 text-yellow-700':
                                                attendance.status === 'late',
                                        }"
                                    >
                                        {{ attendance.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ formatTime(attendance.time_in) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ formatTime(attendance.time_out) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                v-else
                class="text-center text-gray-500 text-sm py-8 border border-dashed rounded-lg"
            >
                No attendance records available.
            </div>
        </div>
    </div>
</template>

<script setup>
import PaginationLinks from "../../Components/PaginationLinks.vue";
import SideBar from "../../Layouts/SideBar.vue";
import { formatDate } from "../../utils/dateFormat";
import { Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { formatTime } from "../../utils/helper";
import { FileWarning } from "lucide-vue-next"; // Lucide icon
import DeleteAttendance from "./DeleteAttendance.vue";
import UpdateAttendance from "./UpdateAttendance.vue";

defineOptions({ layout: SideBar });

const props = defineProps({
    attendances: Object,
    filters: Object,
});

// Filter form data
const filterForm = ref({
    date_from: props.filters?.date_from || "",
    date_to: props.filters?.date_to || "",
    status: props.filters?.status || "",
});

// ✅ Debounced watcher for filters (auto applies changes)
watch(
    filterForm,
    debounce(() => {
        router.get(route("attendances.index"), filterForm.value, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 500),
    { deep: true }
);

// Reset filters
const resetFilters = () => {
    filterForm.value = {
        date_from: "",
        date_to: "",
        status: "",
    };
    router.get(route("attendances.index"));
};

// Status badge colors
const getStatusColor = (status) => {
    const colors = {
        present: "bg-green-100 text-green-800",
        absent: "bg-red-100 text-red-800",
        late: "bg-yellow-100 text-yellow-800",
        on_leave: "bg-blue-100 text-blue-800",
    };
    return colors[status] || "bg-gray-100 text-gray-800";
};

// Format status text
const formatStatus = (status) => {
    return status.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase());
};
</script>

<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Attendances</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Manage and track employee attendance records
                </p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Filters
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Date From
                        </label>
                        <input
                            type="date"
                            v-model="filterForm.date_from"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Date To
                        </label>
                        <input
                            type="date"
                            v-model="filterForm.date_to"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Status
                        </label>
                        <select
                            v-model="filterForm.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">All Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button
                            @click="resetFilters"
                            class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Attendances Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Employee
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Date
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Time In
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Time Out
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Earned Amount
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="attendance in attendances.data"
                                :key="attendance.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ attendance.employee?.name || "N/A" }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ attendance.employee?.email || "" }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatDate(attendance.date) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatTime(attendance.time_in) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatTime(attendance.time_out) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ attendance.earned_amount }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="
                                            getStatusColor(attendance.status)
                                        "
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{ formatStatus(attendance.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <UpdateAttendance
                                        :attendance="attendance"
                                    />
                                    <DeleteAttendance
                                        :attendance="attendance"
                                    />
                                </td>
                            </tr>

                            <!-- ✅ No Records Found (Lucide icon) -->
                            <tr v-if="attendances.data.length === 0">
                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    <FileWarning
                                        class="mx-auto h-12 w-12 text-gray-400"
                                    />
                                    <p class="mt-4 text-lg">
                                        No attendance records found
                                    </p>
                                    <p class="mt-1 text-sm">
                                        Try adjusting your filters or add a new
                                        attendance record
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <PaginationLinks
                    :links="attendances.links"
                    :from="attendances.from"
                    :to="attendances.to"
                    :total="attendances.total"
                />
            </div>
        </div>
    </div>
</template>

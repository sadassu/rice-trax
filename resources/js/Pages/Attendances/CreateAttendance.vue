<script setup>
import { ref, computed } from "vue";
import SideBar from "../../Layouts/SideBar.vue";
import TodayAttendances from "./TodayAttendances.vue";
import { router } from "@inertiajs/vue3";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    employees: Array,
    todayAttendances: Array,
});

const search = ref("");
const selected = ref(null);
const isSubmitting = ref(false);
const errorMessage = ref("");

// Simple frontend search
const filteredEmployees = computed(() => {
    if (!search.value.trim()) return props.employees;
    const term = search.value.toLowerCase();
    return props.employees.filter((emp) =>
        emp.name.toLowerCase().includes(term)
    );
});

const clearSearch = () => {
    search.value = "";
    selected.value = null;
    errorMessage.value = "";
};

const selectEmployee = (employee) => {
    selected.value = employee;
    errorMessage.value = "";
};

const submitForm = () => {
    if (!selected.value || isSubmitting.value) return;

    isSubmitting.value = true;
    errorMessage.value = "";

    router.post(
        "/attendances",
        { employee_id: selected.value.id },
        {
            onFinish: () => {
                isSubmitting.value = false;
            },
            onSuccess: () => {
                clearSearch();
            },
            onError: (errors) => {
                if (errors.attendance) {
                    errorMessage.value = errors.attendance;
                }
            },
        }
    );
};
</script>

<template>
    <div class="max-w-7xl p-4 lg:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-6"
                >
                    <div class="bg-teal-600 px-6 py-4 flex items-center">
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
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-white">
                                Attendance Form
                            </h2>
                            <p class="text-blue-100 text-sm">
                                Mark employee attendance
                            </p>
                        </div>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Search -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Search Employee
                                </label>
                                <div class="relative mt-1">
                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Type employee name..."
                                        class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    />
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                            />
                                        </svg>
                                    </div>
                                    <button
                                        v-if="search"
                                        @click="clearSearch"
                                        type="button"
                                        class="absolute right-3 top-3 p-1 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Selected Employee -->
                            <div
                                v-if="selected"
                                class="bg-green-50 border border-green-200 rounded-lg p-4"
                            >
                                <div class="flex items-start">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 text-green-700 font-semibold"
                                    >
                                        {{
                                            selected.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <div class="flex-1">
                                        <h3
                                            class="font-semibold text-green-900 mb-1"
                                        >
                                            Selected Employee
                                        </h3>
                                        <div
                                            class="space-y-1 text-sm text-green-700"
                                        >
                                            <p>
                                                <b>Name:</b> {{ selected.name }}
                                            </p>
                                            <p>
                                                <b>Role:</b> {{ selected.role }}
                                            </p>
                                            <p>
                                                <b>Rate:</b> ₱{{
                                                    selected.rate
                                                }}/hr
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        @click="clearSearch"
                                        type="button"
                                        class="p-1 text-green-600 hover:text-green-800"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div
                                v-else
                                class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-500 flex items-center"
                            >
                                <svg
                                    class="w-8 h-8 mr-3 text-gray-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                No employee selected
                            </div>

                            <!-- Error -->
                            <div
                                v-if="errorMessage"
                                class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 flex items-center"
                            >
                                <svg
                                    class="w-5 h-5 mr-2 text-red-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                {{ errorMessage }}
                            </div>

                            <!-- Submit -->
                            <button
                                type="submit"
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 flex items-center justify-center"
                                :disabled="!selected || isSubmitting"
                            >
                                <div
                                    v-if="isSubmitting"
                                    class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"
                                />
                                <svg
                                    v-else
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                </svg>
                                {{
                                    isSubmitting
                                        ? "Adding Attendance..."
                                        : "Time in"
                                }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="lg:col-span-3 space-y-6">
                <TodayAttendances :todayAttendances="todayAttendances" />

                <div
                    class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
                >
                    <div
                        class="bg-teal-600 px-6 py-4 flex items-center justify-between"
                    >
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
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">
                                All Employees
                            </h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div
                            v-if="filteredEmployees.length"
                            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="employee in filteredEmployees"
                                :key="employee.id"
                                @click="selectEmployee(employee)"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md hover:border-teal-300 transition-all cursor-pointer group"
                            >
                                <div class="flex items-center mb-3">
                                    <div
                                        class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mr-4 text-teal-700 font-semibold group-hover:bg-teal-200 transition-colors"
                                    >
                                        {{
                                            employee.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <div class="flex-1">
                                        <h4
                                            class="font-medium text-gray-900 group-hover:text-teal-700"
                                        >
                                            {{ employee.name }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            {{ employee.role }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-sm space-y-1">
                                    <p>
                                        <b>Contact:</b> {{ employee.contact }}
                                    </p>
                                    <p><b>Rate:</b> ₱{{ employee.rate }}/hr</p>
                                    <p>
                                        <b>Total Records:</b>
                                        {{ employee.attendances?.length || 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-8 text-gray-500">
                            <svg
                                class="mx-auto h-12 w-12 text-gray-300 mb-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 7a3 3 0 11-6 0 3 3 0 016 0zm-6 7h6a6 6 0 016 6H3a6 6 0 016-6z"
                                />
                            </svg>
                            No employees found
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

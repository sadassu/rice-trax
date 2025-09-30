<script setup>
import { ref, computed, watch, toRef, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import SideBar from "../../Layouts/SideBar.vue";
import { debounce } from "lodash";
import TodayAttendances from "./TodayAttendances.vue";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    employees: Object,
    todayAttendances: Object,
    searchTerm: String,
});

const search = ref(props.searchTerm || "");
const selected = ref(null);
const isSearching = ref(false);
const isSubmitting = ref(false);
const highlightedIndex = ref(-1);

const employees = toRef(props, "employees");

const filteredEmployees = computed(() => {
    if (!search.value) return [];
    return employees.value.data || [];
});

// Get recent employees (those with recent attendance)
const recentEmployees = computed(() => {
    return employees.value.data?.slice(0, 6) || [];
});

const debouncedSearch = debounce((query) => {
    if (query.trim() === "") {
        isSearching.value = false;
        return;
    }

    router.get(
        "/attendances/create",
        { search: query },
        {
            preserveState: true,
            replace: true,
            onStart: () => {
                isSearching.value = true;
            },
            onFinish: () => {
                isSearching.value = false;
                highlightedIndex.value = -1;
            },
        }
    );
}, 300);

watch(search, (newSearch) => {
    if (selected.value && newSearch !== selected.value.name) {
        selected.value = null;
    }

    highlightedIndex.value = -1;

    if (newSearch && newSearch.trim().length > 0) {
        debouncedSearch(newSearch);
    } else {
        isSearching.value = false;
    }
});

const selectEmployee = (emp) => {
    selected.value = emp;
    search.value = emp.name;
    highlightedIndex.value = -1;
};

const clearSearch = () => {
    search.value = "";
    selected.value = null;
    highlightedIndex.value = -1;
    isSearching.value = false;
};

const handleKeyDown = (event) => {
    if (!filteredEmployees.value.length || selected.value) return;

    switch (event.key) {
        case "ArrowDown":
            event.preventDefault();
            highlightedIndex.value = Math.min(
                highlightedIndex.value + 1,
                filteredEmployees.value.length - 1
            );
            scrollToHighlighted();
            break;

        case "ArrowUp":
            event.preventDefault();
            highlightedIndex.value = Math.max(highlightedIndex.value - 1, 0);
            scrollToHighlighted();
            break;

        case "Enter":
            event.preventDefault();
            if (
                highlightedIndex.value >= 0 &&
                highlightedIndex.value < filteredEmployees.value.length
            ) {
                selectEmployee(filteredEmployees.value[highlightedIndex.value]);
            } else if (selected.value) {
                submitForm();
            }
            break;

        case "Escape":
            event.preventDefault();
            clearSearch();
            break;
    }
};

const scrollToHighlighted = async () => {
    await nextTick();
    const highlightedElement = document.querySelector(
        ".employee-item-highlighted"
    );
    if (highlightedElement) {
        highlightedElement.scrollIntoView({ block: "nearest" });
    }
};

const errorMessage = ref("");

const submitForm = () => {
    if (!selected.value || isSubmitting.value) return;

    isSubmitting.value = true;
    errorMessage.value = "";

    router.post(
        "/attendances",
        {
            employee_id: selected.value.id,
        },
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
            <!-- Form Section (Full width on mobile, 1 column on desktop) -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-6"
                >
                    <!-- Card Header -->
                    <div
                        class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4"
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
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                    ></path>
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
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Search Section -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Search Employee
                                </label>
                                <div class="relative">
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
                                            ></path>
                                        </svg>
                                    </div>

                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Type employee name..."
                                        class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        @focus="
                                            search && debouncedSearch(search)
                                        "
                                        @keydown="handleKeyDown"
                                    />

                                    <!-- Loading spinner -->
                                    <div
                                        v-if="isSearching"
                                        class="absolute right-10 top-3.5 w-5 h-5 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin"
                                    ></div>

                                    <!-- Clear button -->
                                    <button
                                        v-if="search && !isSearching"
                                        @click="clearSearch"
                                        class="absolute right-3 top-3 p-1 text-gray-400 hover:text-gray-600 transition-colors"
                                        type="button"
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
                                            ></path>
                                        </svg>
                                    </button>

                                    <!-- Suggestion dropdown -->
                                    <div
                                        v-if="
                                            filteredEmployees.length &&
                                            search &&
                                            !selected
                                        "
                                        class="absolute bg-white border border-gray-200 rounded-lg w-full mt-2 max-h-48 overflow-y-auto z-20 shadow-lg"
                                    >
                                        <div
                                            v-for="(
                                                emp, index
                                            ) in filteredEmployees"
                                            :key="emp.id"
                                            @click="selectEmployee(emp)"
                                            :class="[
                                                'px-4 py-3 cursor-pointer border-b border-gray-100 last:border-b-0 flex items-center transition-colors',
                                                highlightedIndex === index
                                                    ? 'bg-blue-50 employee-item-highlighted border-blue-100'
                                                    : 'hover:bg-gray-50',
                                            ]"
                                        >
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-blue-600 font-medium text-sm"
                                            >
                                                {{
                                                    emp.name
                                                        .charAt(0)
                                                        .toUpperCase()
                                                }}
                                            </div>
                                            <div>
                                                <p
                                                    class="font-medium text-gray-900"
                                                >
                                                    {{ emp.name }}
                                                </p>
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ emp.role }} • ₱{{
                                                        emp.rate
                                                    }}/hr
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Loading message -->
                                    <div
                                        v-if="
                                            isSearching &&
                                            search &&
                                            search.trim().length > 0 &&
                                            !selected
                                        "
                                        class="absolute bg-white border border-gray-200 rounded-lg w-full mt-2 p-4 text-gray-500 z-20 shadow-lg"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                class="w-5 h-5 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin mr-3"
                                            ></div>
                                            <span>Searching employees...</span>
                                        </div>
                                    </div>

                                    <!-- No results message -->
                                    <div
                                        v-if="
                                            search &&
                                            search.trim().length > 0 &&
                                            !filteredEmployees.length &&
                                            !selected &&
                                            !isSearching
                                        "
                                        class="absolute bg-white border border-gray-200 rounded-lg w-full mt-2 p-4 z-20 shadow-lg"
                                    >
                                        <div class="text-center text-gray-500">
                                            <svg
                                                class="mx-auto h-12 w-12 text-gray-300 mb-2"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                ></path>
                                            </svg>
                                            <p class="font-medium">
                                                No employees found
                                            </p>
                                            <p class="text-sm">
                                                Try adjusting your search term
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keyboard navigation help -->
                                <div
                                    v-if="
                                        filteredEmployees.length &&
                                        search &&
                                        !selected
                                    "
                                    class="flex items-center text-xs text-gray-500 bg-gray-50 rounded-md p-2"
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
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        ></path>
                                    </svg>
                                    Use ↑↓ arrows to navigate, Enter to select,
                                    Esc to clear
                                </div>
                            </div>

                            <!-- Selected Employee Section -->
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
                                        <div class="space-y-1">
                                            <p class="text-sm text-green-700">
                                                <span class="font-medium"
                                                    >Name:</span
                                                >
                                                {{ selected.name }}
                                            </p>
                                            <p class="text-sm text-green-700">
                                                <span class="font-medium"
                                                    >Role:</span
                                                >
                                                {{ selected.role }}
                                            </p>
                                            <p class="text-sm text-green-700">
                                                <span class="font-medium"
                                                    >Rate:</span
                                                >
                                                ₱{{ selected.rate }}/hr
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        @click="clearSearch"
                                        type="button"
                                        class="p-1 text-green-600 hover:text-green-800 transition-colors"
                                        title="Clear selection"
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
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div
                                v-else
                                class="bg-gray-50 border border-gray-200 rounded-lg p-4"
                            >
                                <div class="flex items-center text-gray-500">
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
                                        ></path>
                                    </svg>
                                    <span>No employee selected</span>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <div
                                v-if="errorMessage"
                                class="bg-red-50 border border-red-200 rounded-lg p-4"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-5 h-5 text-red-400 mr-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    <p class="text-red-700 font-medium">
                                        {{ errorMessage }}
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-medium transition-colors duration-200 shadow-sm"
                                    :disabled="!selected || isSubmitting"
                                >
                                    <div
                                        v-if="isSubmitting"
                                        class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"
                                    ></div>
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
                                        ></path>
                                    </svg>
                                    {{
                                        isSubmitting
                                            ? "Adding Attendance..."
                                            : "Add Attendance"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cards Section (3 columns on desktop, full width on mobile) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Today's Attendances -->
                <TodayAttendances :todayAttendances="todayAttendances" />

                <!-- Recent Employees -->
                <div
                    class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden"
                >
                    <div
                        class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4"
                    >
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
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3
                                        class="text-lg font-semibold text-white"
                                    >
                                        Recent Employees
                                    </h3>
                                    <p class="text-purple-100 text-sm">
                                        Quick access for attendance
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div
                            v-if="recentEmployees.length"
                            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="employee in recentEmployees"
                                :key="employee.id"
                                @click="selectEmployee(employee)"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md hover:border-purple-300 transition-all cursor-pointer group"
                            >
                                <div class="flex items-center mb-3">
                                    <div
                                        class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4 text-purple-700 font-semibold group-hover:bg-purple-200 transition-colors"
                                    >
                                        {{
                                            employee.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <div class="flex-1">
                                        <h4
                                            class="font-medium text-gray-900 group-hover:text-purple-700 transition-colors"
                                        >
                                            {{ employee.name }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            {{ employee.role }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500"
                                            >Contact:</span
                                        >
                                        <span class="font-medium">{{
                                            employee.contact
                                        }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Rate:</span>
                                        <span class="font-medium text-green-600"
                                            >₱{{ employee.rate }}/hr</span
                                        >
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500"
                                            >Total Records:</span
                                        >
                                        <span class="font-medium">{{
                                            employee.attendances?.length || 0
                                        }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div
                                        class="flex items-center text-purple-600 text-sm font-medium group-hover:text-purple-700 transition-colors"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            ></path>
                                        </svg>
                                        Click to add attendance
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                            <p class="text-gray-500 font-medium">
                                No employees available
                            </p>
                            <p class="text-sm text-gray-400">
                                Add some employees to get started
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

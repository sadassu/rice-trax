<script setup>
import axios from "axios";
import SideBar from "../../Layouts/SideBar.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
defineOptions({ layout: SideBar });
const props = defineProps({
    employee: Object,
});
// form data
const form = ref({
    start_date: null,
    end_date: null,
});
// result data (salary computation)
const result = ref(null);
// loading state
const loading = ref(false);
// submit request
const submit = async () => {
    loading.value = true;
    result.value = null;
    try {
        const res = await axios.post(
            route("employees.computeSalary", props.employee.id),
            {
                start_date: form.value.start_date,
                end_date: form.value.end_date,
            }
        );
        result.value = res.data; // JSON response from controller
    } catch (err) {
        console.error("Error computing salary", err.response?.data || err);
        alert("Failed to compute salary. Check console for details.");
    } finally {
        loading.value = false;
    }
};

const goBack = () => {
    router.visit(route("employees.index"));
};
</script>
<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Back Button -->
            <button
                @click="goBack"
                class="mb-4 flex items-center gap-2 text-slate-600 hover:text-slate-900 transition-colors font-medium"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back to Employees
            </button>

            <!-- Header Card -->
            <div
                class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">
                            Salary Computation
                        </h2>
                        <p class="text-slate-600">{{ employee.name }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div
                class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6"
            >
                <h3 class="text-lg font-semibold text-slate-800 mb-4">
                    Select Period
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            Start Date
                        </label>
                        <input
                            type="date"
                            v-model="form.start_date"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            End Date
                        </label>
                        <input
                            type="date"
                            v-model="form.end_date"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        />
                    </div>
                </div>
                <button
                    @click="submit"
                    :disabled="loading"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-md hover:shadow-lg"
                >
                    <span
                        v-if="!loading"
                        class="flex items-center justify-center gap-2"
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
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                            />
                        </svg>
                        Compute Salary
                    </span>
                    <span v-else class="flex items-center justify-center gap-2">
                        <svg
                            class="w-5 h-5 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        Computing...
                    </span>
                </button>
            </div>

            <!-- Result Card -->
            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 transform translate-y-4"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="result"
                    class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden"
                >
                    <!-- Header -->
                    <div
                        class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4"
                    >
                        <h3
                            class="text-xl font-bold text-white flex items-center gap-2"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            Salary Computation Result
                        </h3>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-4">
                        <div
                            class="flex items-center justify-between py-3 border-b border-slate-200"
                        >
                            <span class="text-slate-600 font-medium"
                                >Employee</span
                            >
                            <span class="text-slate-900 font-semibold">{{
                                result.employee_name
                            }}</span>
                        </div>

                        <div
                            class="flex items-center justify-between py-3 border-b border-slate-200"
                        >
                            <span class="text-slate-600 font-medium"
                                >Total Hours</span
                            >
                            <span class="text-slate-900 font-semibold"
                                >{{ result.total_hours }} hrs</span
                            >
                        </div>

                        <div
                            class="flex items-center justify-between py-3 border-b border-slate-200"
                        >
                            <span class="text-slate-600 font-medium"
                                >Period</span
                            >
                            <span class="text-slate-900 font-semibold text-sm">
                                {{ result.period.start_date }} →
                                {{ result.period.end_date }}
                            </span>
                        </div>

                        <!-- Total Salary Highlight -->
                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 mt-4 border border-green-200"
                        >
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-green-800 font-semibold text-lg"
                                    >Total Salary</span
                                >
                                <span class="text-3xl font-bold text-green-700">
                                    ₱{{
                                        Number(
                                            result.total_salary
                                        ).toLocaleString("en-PH", {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2,
                                        })
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

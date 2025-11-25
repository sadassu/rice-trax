<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { formatCurrency } from "../../utils/currencyFormat";
import { formatDate } from "../../utils/dateFormat";
import ShowAttendance from "../Attendances/ShowAttendance.vue";
import CreateEmployee from "./CreateEmployee.vue";
import { Link } from "@inertiajs/vue3";
import EditEmployee from "./EditEmployee.vue";
import DeleteEmployee from "./DeleteEmployee.vue";
import { router } from "@inertiajs/vue3";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import { isSafeRowClick } from "../../utils/eventHelper";
import { Banknote, UsersRound } from "lucide-vue-next";

defineOptions({ layout: SideBar });
defineProps({
    employees: Object,
});

const goToSalary = (employeeId) => {
    router.get(route("employees.computeSalary.page", employeeId));
};

function onRowClick(event, id) {
    if (!isSafeRowClick(event)) return; // skip row click
    router.visit(route("employees.show", id));
}
</script>
<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Employee Management
            </h1>
            <p class="text-gray-600">
                Manage your team members and track their information
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50">
            <!-- Header with Actions -->
            <div class="p-6 border-b border-gray-200/50">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                >
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            Employee Directory
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ employees.total || employees.data.length }} total
                            employees
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <Link
                            :href="route('attendances.create')"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-lime-500 hover:bg-lime-600 rounded-xl transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
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
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            Attendance Forms
                        </Link>

                        <CreateEmployee />
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Employee Name
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Role
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Rate
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            <tr
                                v-for="employee in employees.data"
                                :key="employee.id"
                                @click="onRowClick($event, employee.id)"
                                class="cursor-pointer hover:bg-gray-200 transition-colors duration-150"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-4"
                                        >
                                            {{
                                                employee.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-semibold text-gray-900"
                                            >
                                                {{ employee.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Employee ID: #{{
                                                    String(
                                                        employee.id
                                                    ).padStart(4, "0")
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700"
                                    >
                                        {{ employee.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ formatCurrency(employee.rate) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        per hr
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap flex gap-3"
                                >
                                    <ShowAttendance :employee="employee" />
                                    <button
                                        @click="goToSalary(employee.id)"
                                        title="Compute Salary"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                                    >
                                        <Banknote />
                                    </button>
                                    <EditEmployee :employee="employee" />
                                    <DeleteEmployee :employee="employee" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View (visible on mobile/tablet) -->
            <div class="lg:hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6">
                    <div
                        v-for="employee in employees.data"
                        :key="employee.id"
                        class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-sm border border-gray-200/50 hover:shadow-md transition-all duration-200 hover:scale-[1.02]"
                    >
                        <!-- Employee Header -->
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg mr-4 shadow-md"
                            >
                                {{ employee.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="flex-1">
                                <div class="text-lg font-bold text-gray-900">
                                    {{ employee.name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    ID: #{{
                                        String(employee.id).padStart(4, "0")
                                    }}
                                </div>
                            </div>
                        </div>

                        <!-- Employee Details -->
                        <div class="space-y-3">
                            <!-- Contact -->
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3"
                                >
                                    <svg
                                        class="w-4 h-4 text-emerald-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ employee.contact }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Phone Number
                                    </div>
                                </div>
                            </div>

                            <!-- Date Joined -->
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3"
                                >
                                    <svg
                                        class="w-4 h-4 text-blue-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ formatDate(employee.created_at) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Member since
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3"
                            >
                                <svg
                                    class="w-4 h-4 text-purple-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M6 6a4 4 0 118 0v1h1a2 2 0 012 2v6a2 2 0 01-2 2h-1v1a4 4 0 11-8 0v-1H5a2 2 0 01-2-2V9a2 2 0 012-2h1V6zm2 1V6a2 2 0 114 0v1H8z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ employee.role }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Position
                                </div>
                            </div>
                        </div>

                        <!-- Rate -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3"
                            >
                                <svg
                                    class="w-4 h-4 text-amber-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M11 17a1 1 0 102 0 1 1 0 00-2 0zm-4 0a1 1 0 102 0 1 1 0 00-2 0zm6-4V9a4 4 0 10-8 0v4a4 4 0 108 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ formatCurrency(employee.rate) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Daily Rate
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex mt-5 pt-4 border-t gap-3 border-gray-200"
                        >
                            <ShowAttendance :employee="employee" />
                            <button
                                @click="goToSalary(employee.id)"
                                title="Compute Salary"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                            >
                                <Banknote />
                            </button>
                            <EditEmployee :employee="employee" />
                            <DeleteEmployee :employee="employee" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="employees.data.length === 0" class="text-center py-16">
                <div
                    class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <UsersRound />
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    No employees found
                </h3>
                <p class="text-gray-500 mb-6">
                    Get started by adding your first team member.
                </p>
                <CreateEmployee />
            </div>

            <!-- Pagination (if needed) -->
            <PaginationLinks
                :links="employees.links"
                :from="employees.from"
                :to="employees.to"
                :total="employees.total"
            />
        </div>
    </div>
</template>

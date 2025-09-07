<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import ShowAttendance from "./ShowAttendance.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({ layout: SideBar });
defineProps({
    employees: Object,
});

// format date nicely
const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
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

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="relative bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl shadow-lg p-6 overflow-hidden"
            >
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"
                ></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium opacity-90">
                            Total Employees
                        </h3>
                        <div
                            class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold">
                        {{ employees.total || employees.data.length }}
                    </p>
                    <div class="mt-2 text-sm opacity-75">
                        Active team members
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-2xl shadow-lg p-6 overflow-hidden"
            >
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"
                ></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium opacity-90">
                            New This Month
                        </h3>
                        <div
                            class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold">3</p>
                    <div class="mt-2 text-sm opacity-75">Recent hires</div>
                </div>
            </div>
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
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
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

                        <Link
                            :href="route('employees.create')"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-xl transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg"
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                            Add New Employee
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Employee Name
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                                            />
                                        </svg>
                                        Contact Info
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Date Joined
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            <tr
                                v-for="employee in employees.data"
                                :key="employee.id"
                                class="hover:bg-gray-50/50 transition-colors duration-150"
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
                                    <div
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        {{ employee.contact }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Phone Number
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        {{ formatDate(employee.created_at) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Member since
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <ShowAttendance :employee="employee" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty state -->
                    <div
                        v-if="employees.data.length === 0"
                        class="text-center py-16"
                    >
                        <div
                            class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-12 h-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            No employees found
                        </h3>
                        <p class="text-gray-500 mb-6">
                            Get started by adding your first team member.
                        </p>
                        <Link
                            :href="route('employees.create')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200"
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                            Add First Employee
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Pagination (if needed) -->
            <div
                v-if="employees.links && employees.links.length > 3"
                class="bg-gray-50 px-6 py-3 border-t border-gray-200 mt-4 rounded-b-2xl"
            >
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="text-sm text-gray-700">
                        {{ employees.from }}-{{ employees.to }} of
                        {{ employees.total }} results
                    </div>
                    <div class="flex gap-1 flex-wrap">
                        <button
                            v-for="link in employees.links"
                            :key="link.label"
                            @click="link.url ? $inertia.get(link.url) : null"
                            v-html="link.label"
                            class="px-3 py-1 text-sm rounded-md transition-all duration-200"
                            :class="{
                                'text-gray-400 cursor-not-allowed': !link.url,
                                'bg-gray-900 text-white': link.active,
                                'text-gray-700 hover:bg-gray-200 hover:scale-105':
                                    link.url && !link.active,
                            }"
                            :disabled="!link.url"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

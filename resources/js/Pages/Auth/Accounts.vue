<script setup>
import SideBar from "../../Layouts/SideBar.vue";

defineOptions({ layout: SideBar });

defineProps({
    accounts: Object,
});
</script>

<template>
    <div class="m-6">
        <div class="mb-4 text-xl font-semibold text-gray-700">
            Employee Accounts
        </div>

        <!-- Register Button -->
        <Link
            class="cursor-pointer bg-blue-600 hover:bg-blue-700 transition rounded-xl px-4 py-2 text-white font-medium shadow-md"
            :href="route('register')"
        >
            + Register New Account
        </Link>

        <!-- Table Container -->
        <div
            class="mt-6 overflow-x-auto shadow-md rounded-xl border border-gray-200"
        >
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead
                    class="bg-gray-100 text-gray-700 text-sm uppercase font-semibold"
                >
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr
                        v-for="user in accounts.data"
                        :key="user.id"
                        class="hover:bg-gray-50 transition"
                    >
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ user.name }}
                        </td>
                        <td class="px-6 py-4">{{ user.email }}</td>
                        <td class="px-6 py-4">{{ user.role }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

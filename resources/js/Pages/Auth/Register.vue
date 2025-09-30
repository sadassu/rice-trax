<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";
import SideBar from "../../Layouts/SideBar.vue";

defineOptions({
    layout: SideBar,
});

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    role: "cashier",
});

const submit = () => {
    form.post(route("register"), {
        onError: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head :title="`| ${$page.component}`" />

    <div
        class="max-w-lg mx-auto mt-10 p-8 bg-white shadow-md rounded-xl border border-gray-200"
    >
        <h1 class="text-2xl font-semibold text-gray-700 mb-6">
            Create New Employee Account
        </h1>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Name -->
            <div>
                <TextInput
                    name="name"
                    v-model="form.name"
                    :message="form.errors.name"
                    class="w-full"
                />
            </div>

            <!-- Email -->
            <div>
                <TextInput
                    name="email"
                    type="email"
                    v-model="form.email"
                    :message="form.errors.email"
                    class="w-full"
                />
            </div>

            <!-- Password -->
            <div>
                <TextInput
                    name="password"
                    type="password"
                    v-model="form.password"
                    :message="form.errors.password"
                    class="w-full"
                />
            </div>

            <!-- Confirm Password -->
            <div>
                <TextInput
                    name="password confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    class="w-full"
                />
            </div>

            <!-- Role -->
            <div>
                <label
                    for="role"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Role
                </label>
                <select
                    id="role"
                    v-model="form.role"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="cashier">Cashier</option>
                </select>
                <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">
                    {{ form.errors.role }}
                </div>
            </div>

            <!-- Submit Button -->
            <button
                :disabled="form.processing"
                type="submit"
                class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ form.processing ? "Processing..." : "Create Account" }}
            </button>
        </form>
    </div>
</template>

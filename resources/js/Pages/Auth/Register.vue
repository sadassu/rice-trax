<script setup>
import { ref } from "vue";
import { useForm, Head } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";
import SideBar from "../../Layouts/SideBar.vue";

// Lucide icons
import { Eye, EyeOff, Mail, User } from "lucide-vue-next";

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

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

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
            Create Account
        </h1>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Name -->
            <TextInput
                name="name"
                v-model="form.name"
                :message="form.errors.name"
            >
                <template #icon>
                    <User class="w-5 h-5 text-gray-500" />
                </template>
            </TextInput>

            <!-- Email -->
            <TextInput
                name="email"
                type="email"
                v-model="form.email"
                :message="form.errors.email"
            >
                <template #icon>
                    <Mail class="w-5 h-5 text-gray-500" />
                </template>
            </TextInput>

            <!-- Password -->
            <TextInput
                name="password"
                :type="showPassword ? 'text' : 'password'"
                v-model="form.password"
                :message="form.errors.password"
            >
                <template #icon>
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <component
                            :is="showPassword ? EyeOff : Eye"
                            class="w-5 h-5"
                        />
                    </button>
                </template>
            </TextInput>

            <!-- Confirm Password -->
            <TextInput
                name="password confirmation"
                :type="showPasswordConfirm ? 'text' : 'password'"
                v-model="form.password_confirmation"
                :message="form.errors.password_confirmation"
            >
                <template #icon>
                    <button
                        type="button"
                        @click="showPasswordConfirm = !showPasswordConfirm"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <component
                            :is="showPasswordConfirm ? EyeOff : Eye"
                            class="w-5 h-5"
                        />
                    </button>
                </template>
            </TextInput>

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
                    class="w-full px-4 py-2 border rounded-2xl shadow-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 border-gray-300"
                >
                    <option value="admin">Admin</option>
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

<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";
import { ref } from "vue";

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

const showPassword = ref(false);
const isLoading = ref(false);

const submit = () => {
    isLoading.value = true;
    form.post(route("login"), {
        onError: () => {
            form.reset("password");
            isLoading.value = false;
        },
        onSuccess: () => {
            isLoading.value = false;
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <Head :title="`Login | ${$page.component}`" />

    <div
        class="min-h-screen bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-100 flex items-center justify-center p-4"
    >
        <div class="w-full max-w-md">
            <!-- Logo/Brand area -->
            <div class="text-center mb-8">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-teal-600 to-emerald-600 rounded-full mx-auto mb-4 flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Welcome back
                </h1>
                <p class="text-gray-600">Sign in to your account to continue</p>
            </div>

            <!-- Login Form Card -->
            <div
                class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-center">
                            <svg
                                class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                />
                            </svg>
                            <TextInput
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                v-model="form.email"
                                :message="form.errors.email"
                                class="pl-3 w-full"
                                placeholder="Enter your email"
                            />
                        </div>
                        <div
                            v-if="form.errors.email"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-center">
                            <svg
                                class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                            <TextInput
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                v-model="form.password"
                                :message="form.errors.password"
                                class="pl-3 w-full "
                                placeholder="Enter your password"
                            />
                            <button
                                type="button"
                                @click="togglePasswordVisibility"
                                class=" inset-y-0 pl-3 flex items-center text-gray-400 hover:text-teal-600 transition-colors duration-200 z-10"
                            >
                                <svg
                                    v-if="showPassword"
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div
                            v-if="form.errors.password"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                type="checkbox"
                                v-model="form.remember"
                                class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded transition-colors duration-200"
                            />
                            <label
                                for="remember"
                                class="ml-2 block text-sm text-gray-700 cursor-pointer"
                            >
                                Remember me
                            </label>
                        </div>
                        <!-- <Link
                            :href="route('password.request')"
                            class="text-sm text-teal-600 hover:text-teal-800 transition-colors duration-200"
                        >
                            Forgot password?
                        </Link> -->
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing || isLoading"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <svg
                            v-if="form.processing || isLoading"
                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
                        <span v-if="form.processing || isLoading"
                            >Signing in...</span
                        >
                        <span v-else>Sign in</span>
                    </button>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500"
                                >Don't have an account?</span
                            >
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <Link
                            :href="route('register')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200"
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
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                />
                            </svg>
                            Create new account
                        </Link>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>© 2025 Your Company. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>

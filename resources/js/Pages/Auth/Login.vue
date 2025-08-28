<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";

const form = useForm({
    email: null,
    password: null,
    remember: null,
});

const submit = () => {
    form.post(route("login"), {
        onError: () => form.reset("password"),
    });
};
</script>

<template>
    <Head :title="`| ${$page.component}`" />

    <form @submit.prevent="submit">
        <h1 class="mb-6">Login to your account</h1>
        <TextInput
            name="email"
            type="email"
            v-model="form.email"
            :message="form.errors.email"
        />
        <TextInput
            name="password"
            type="password"
            v-model="form.password"
            :message="form.errors.password"
        />
        <div class="flex items-center gap-2">
            <label>Remember me</label>
            <input type="checkbox" v-model="form.remember" />
        </div>
        <button :disabled="form.processing">Login</button>
        <div>
            <Link :href="route('register')" class="text-blue-500"
                >I don't have account</Link
            >
        </div>
    </form>
</template>

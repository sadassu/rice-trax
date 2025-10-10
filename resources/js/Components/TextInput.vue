<script setup>
const model = defineModel({
    type: null,
    required: true,
});

defineProps({
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: "text",
    },
    message: String,
});
</script>

<template>
    <div class="mb-6">
        <!-- Label -->
        <label
            class="block mb-2 text-sm font-semibold text-gray-700 capitalize"
        >
            {{ name }}
        </label>

        <!-- Input Wrapper -->
        <div class="relative">
            <input
                :type="type"
                v-model="model"
                class="w-full px-4 py-2 border rounded-2xl shadow-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 border-gray-300"
                :class="{
                    '!border-red-500 !ring-red-500 ring-2': message,
                    'pr-10': $slots.icon, // add right padding if icon is present
                }"
                :placeholder="`Enter ${name}`"
            />

            <!-- Optional right-side icon -->
            <div
                v-if="$slots.icon"
                class="absolute inset-y-0 right-3 flex items-center"
            >
                <slot name="icon" />
            </div>
        </div>

        <!-- Error message -->
        <small v-if="message" class="mt-1 block text-sm text-red-500">
            {{ message }}
        </small>
    </div>
</template>

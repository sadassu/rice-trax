<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const visible = ref(false);
const message = ref("");

watch(
    () => page.props.flash?.message,
    (newMessage) => {
        if (newMessage) {
            message.value = newMessage;
            visible.value = true;

            setTimeout(() => {
                visible.value = false;
                message.value = "";
            }, 2000);
        }
    },
    { immediate: true }
);
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-[-10px]"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-300"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-[-10px]"
    >
        <div
            v-if="visible"
            class="fixed top-4 right-4 bg-teal-600 text-white px-4 py-2 rounded-lg shadow-lg z-50"
        >
            {{ message }}
        </div>
    </transition>
</template>

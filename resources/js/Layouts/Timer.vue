<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import Notifications from "../Components/Notifications.vue";

const currentTime = ref("");

let timer = null;

const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleString(); // formats based on user’s locale
};

onMounted(() => {
    updateTime(); // set initial time
    timer = setInterval(updateTime, 1000); // update every second
});

onBeforeUnmount(() => {
    clearInterval(timer); // cleanup interval
});
</script>

<template>
    <div class="fixed top-0 right-0 flex">
        <div>
            <Notifications />
        </div>

        <div class="bg-lime-900 text-white px-4 rounded-b-2xl">
            Current Time: {{ currentTime }}
        </div>
    </div>
</template>

<!-- Components/Modal.vue -->
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: "" },
    subtitle: { type: String, default: "" },
    icon: { type: String, default: "" }, // optional: pass heroicon name or slot
    danger: { type: Boolean, default: false },
    processing: { type: Boolean, default: false },
});

const emit = defineEmits(["close"]);

const handleEscape = (e) => {
    if (e.key === "Escape" && !props.processing) {
        emit("close");
    }
};

onMounted(() => document.addEventListener("keydown", handleEscape));
onBeforeUnmount(() => document.removeEventListener("keydown", handleEscape));
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                @click="$emit('close')"
            >
                <!-- Modal Content -->
                <Transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div
                        v-if="show"
                        class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-auto"
                        @click.stop
                    >
                        <!-- Header -->
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                v-if="icon"
                                class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center"
                                :class="danger ? 'bg-red-100' : 'bg-gray-100'"
                            >
                                <slot name="icon">
                                    <!-- default icon if provided -->
                                    <svg
                                        v-if="danger"
                                        class="w-6 h-6 text-red-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                                            1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732
                                            0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                        />
                                    </svg>
                                </slot>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ title }}
                                </h3>
                                <p
                                    v-if="subtitle"
                                    class="text-sm text-gray-500 mt-1"
                                >
                                    {{ subtitle }}
                                </p>
                            </div>
                        </div>

                        <!-- Content Slot -->
                        <div class="mb-6">
                            <slot />
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 justify-end">
                            <slot name="actions" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

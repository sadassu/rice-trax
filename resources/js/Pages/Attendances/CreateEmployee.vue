<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "../../Components/TextInput.vue";

const showModal = ref(false);

const form = useForm({
    name: null,
    contact: null,
    salary: null,
});

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    if (!form.processing) {
        showModal.value = false;
        form.reset();
        form.clearErrors();
    }
};

const submit = () => {
    form.post(route("employees.store"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

// Escape key close
const handleEscape = (e) => {
    if (e.key === "Escape" && !form.processing) {
        closeModal();
    }
};
</script>

<template>
    <button
        @click="openModal"
        class="group inline-flex items-center gap-2 px-4 py-2.5 border border-blue-200 rounded-xl text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-300 transition-all duration-200 shadow-sm hover:shadow"
    >
        <svg
            class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
            />
        </svg>
        Add Employee
    </button>

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
                v-if="showModal"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                @click="closeModal"
                @keydown="handleEscape"
                tabindex="0"
            >
                <!-- Modal content -->
                <Transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div
                        v-if="showModal"
                        class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-auto"
                        @click.stop
                    >
                        <!-- Header -->
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Add Employees
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Fill in the details to add a employee.
                                </p>
                            </div>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submit" class="space-y-4">
                            <TextInput
                                name="name"
                                placeholder="Name"
                                v-model="form.name"
                                :message="form.errors.name"
                            />
                            <TextInput
                                name="Contact"
                                placeholder="Contact"
                                v-model="form.contact"
                                :message="form.errors.contact"
                            />

                            <TextInput
                                name="Salary"
                                placeholder="0000"
                                v-model="form.salary"
                                type="number"
                                :message="form.errors.salary"
                            />
                            <!-- Actions -->
                            <div class="flex gap-3 justify-end pt-4">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    :disabled="form.processing"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 min-w-[100px] justify-center"
                                >
                                    <svg
                                        v-if="form.processing"
                                        class="animate-spin w-4 h-4"
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
                                        />
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373
                                               0 0 5.373 0 12h4zm2 5.291A7.962
                                               7.962 0 014 12H0c0 3.042 1.135
                                               5.824 3 7.938l3-2.647z"
                                        />
                                    </svg>
                                    <span>{{
                                        form.processing
                                            ? "Submitting..."
                                            : "Submit"
                                    }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    employee: Object,
});

const showModal = ref(false);

const form = useForm({
    employee_id: props.employee.employee_id,
    name: props.employee.name,
    contact: props.employee.contact,
    rate: props.employee.rate,
    role: props.employee.role,
});

const openModal = () => (showModal.value = true);

const closeModal = () => {
    if (!form.processing) {
        showModal.value = false;
        form.reset();
        form.clearErrors();
    }
};

const submit = () => {
    form.put(route("employees.update", props.employee.id), {
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <!-- Trigger -->
    <button
        @click="openModal"
        class="cursor-pointer inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition"
    >
        <svg
            class="w-4 h-4 mr-1"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
            ></path>
        </svg>
        Edit
    </button>

    <!-- Modal -->
    <Modal
        :show="showModal"
        title="Update Employee"
        subtitle="Modify employee details below"
        :processing="form.processing"
        @close="closeModal"
    >
        <!-- Form Fields in default slot -->
        <form @submit.prevent="submit" class="space-y-4">
            <TextInput
                class="hidden"
                name="employee_id"
                v-model="form.employee_id"
                :message="form.errors.employee_id"
            />
            <TextInput
                name="Name"
                placeholder="Employee Name"
                v-model="form.name"
                :message="form.errors.name"
            />
            <TextInput
                name="Contact"
                placeholder="Contact Number"
                v-model="form.contact"
                :message="form.errors.contact"
            />
            <TextInput
                name="Rate"
                placeholder="Rate (Number only)"
                v-model="form.rate"
                :message="form.errors.rate"
            />
            <div>
                <label
                    for="role"
                    placeholder="Enter Role"
                    class="block mb-2 text-sm font-semibold text-gray-700 capitalize"
                    >Role
                </label>
                <select
                    id="role"
                    v-model="form.role"
                    class="w-full px-4 py-2 border rounded-2xl shadow-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 border-gray-300"
                >
                    <option value="" disabled>Select Role</option>
                    <option value="Cashier">Cashier</option>
                    <option value="Helper">Helper</option>
                    <option value="Manager">Manager</option>
                </select>

                <small
                    v-if="form.errors.role"
                    class="mt-1 block text-sm text-red-500"
                >
                    {{ form.errors.role }}
                </small>
            </div>
        </form>

        <!-- Actions slot - OUTSIDE the form -->
        <template #actions>
            <button
                type="button"
                @click="closeModal"
                class="px-4 py-2.5 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition"
                :disabled="form.processing"
            >
                Cancel
            </button>
            <button
                type="submit"
                @click="submit"
                class="px-4 py-2.5 rounded-lg bg-green-600 text-white hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing"
            >
                <span v-if="form.processing">Updating...</span>
                <span v-else>Update</span>
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia-vue3'
import { ref } from "@vue/reactivity"

const props = defineProps({
    professional: object,
    appointments: object,
    userAppointment: [object, array],
});

const professional = ref(JSON.parse(JSON.stringify(props.professional)))
const appointments = ref(JSON.parse(JSON.stringify(props.appointments)))
const userAppointment = ref(JSON.parse(JSON.stringify(props.userAppointment)))

const destroy = (id) => {
    Inertia.delete(route('appointment.destroy', id))
}

</script>

<template>
    <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
        <!-- Owner -->
        <h1 class="text-3xl font-bold">Owner</h1>
        <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md">
            <div class="flex justify-between">
                <div class="px-6 py-6 font-bold">
                    {{ 1 }}. {{ professional.professional_title }} {{ professional.first_name }} {{ professional.last_name }}
                </div>
            </div>
            <div class="flex flex-col items-left px-6 pb-6">
                <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                    <img v-if="professional.image" :src="'/storage/' + professional.image"
                        class="w-64 h-48 object-cover" />
                    <svg v-else class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
                <p class="text-base font-medium text-slate-600 mt-3">{{ professional.professional_description }}</p>
            </div>
        </div>
        <!-- Member -->
        <h1 class="text-3xl font-bold">Member</h1>
        <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                <!-- Table -->
                <table
                    class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                    <thead class="text-xs text-gray-700 uppercase">
                        <tr class="bg-white border-b border-gray-400">
                            <th scope="col" class="py-3 px-6">#</th>
                            <th scope="col" class="py-3 px-6">User</th>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">Email</th>
                            <th scope="col" class="py-3 px-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appointment, index) in appointments" class="bg-white border-b border-gray-400">
                            <td data-label="Title" class="py-4 px-6">
                                {{ index + 1 }}
                            </td>
                            <td data-label="Title" class="py-4 px-6">
                                {{ appointment.name }}
                            </td>
                            <td data-label="User_id" class="py-4 px-6">
                                {{ appointment.first_name }} {{ appointment.last_name }}
                            </td>
                            <td data-label="Description" class="py-4 px-6">
                                {{ appointment.email }}
                            </td>
                            <td v-if="appointment.id === userAppointment.id">
                                <Link @click="destroy(userAppointment.id)" class="inline-flex items-center text-left fill-white text-white w-30 bg-red-400 hover:bg-red-500 font-semibold py-2 px-4 border border-transparent rounded">
                                <box-icon class='mr-1' name='exit'></box-icon>
                                <span class="mr-2 inline-block align-top">Exit </span>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--/ Table -->
            </div>
        </div>
    </div>
</template>
<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import QuestionEditor from "@/Components/Editor/QuestionEditor.vue";
import Tiptap from '@/Components/Tiptap.vue'
// Import Inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Import Uuid
import { v4 as uuidv4 } from "uuid";

import {loadStripe} from '@stripe/stripe-js';

// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        QuestionEditor,
        loadStripe,
        Tiptap,
        Inertia,
        useForm,
        Head,
    },
    props: {
        appointment: Object, default: () => ({}),
        event: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
    data() {
        return {
            image_url: null,
        }
    },
    setup(props) {
        // Form
        const form = useForm({
            appointment_id: props.appointment.id,
            amount: props.event.price,
            status: null,
            method: null, 
            card_name: null,
            card_number: null,
            card_cvc: null,
            card_expiry_month: null,
            card_expiry_year: null,
        });

        const stripe = loadStripe('pk_test_51M8KvWAhQy7LWfcMfxUfr1zQALbxjMobN3rBUtrjEJuXyA6PadDa6Ynuzsu7lPlJp7fuLxDUuRyOqC9TTMvxfv5t00SJiJQkco');

        // Contents Store 
        function submit() {
            Inertia.post(route('payment.store'), form)
        }
        return { form, submit };
    },
    methods: {

    }
}
</script>


<template>
    <!-- Header -->
    <Head title="User Payment Create" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Transaction</p>
                Payment: {{ event.title }}
            </div>
            <!--/ Title Header -->
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Create New Payment
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                    <div class="pt-4">
                        <div v-if="event.image" class="sm:overflow-hidden sm:rounded-md">
                            <img :src="'/storage/' + event.image" class="w-199 h-100 object-cover" />
                        </div>
                        <ul class="list-disc pt-4">
                            <li class="flow-root">
                                <p class="inline-flex items-center text-left w-full fill-white bg-indigo-400 text-white font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class='mr-2' name='cube'></box-icon> 
                                    <span class="inline-block align-top text-base">{{ appointment.start_date }} {{ appointment.start_time }}</span>
                                </p>
                            </li>
                        </ul>
                    </div>
                </template>
                <!--/ #Subtitle -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- Form -->
                        <form @submit.prevent="submit">
                            <div class="sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-xl font-bold text-indigo-500">Create Payment</h1>
                                    <div class="grid grid-cols-12 gap-6">
                                    <!-- Title -->
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Name on Card</label>
                                        <input v-model="form.card_name" type="text" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->

                                    <!-- Title -->
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Card Number</label>
                                        <input v-model="form.card_number" type="text" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->

                                
                                    <!-- Title -->
                                    <div class="col-span-12 sm:col-span-4">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Card CVC</label>
                                        <input v-model="form.card_cvc" type="number" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->

                                    <!-- Title -->
                                    <div class="col-span-12 sm:col-span-4">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Card Expiracy Date</label>
                                        <input v-model="form.card_expiry_month" type="number" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->

                                    <!-- Title -->
                                    <div class="col-span-12 sm:col-span-4">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Card Expiracy Year</label>
                                        <input v-model="form.card_expiry_year" type="number" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->
                                </div>

                                </div>

                                <!-- Submit -->
                                <div class="px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent fill-white bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save The Payment</span>
                                    </button>
                                </div>
                                <!--/ Submit -->
                            </div>
                        </form>
                        <!--/ Form -->
                    </div>
                </template>
                <!--/ #Main -->
            </ContainerWithSideBar>
            <!--/ Container With Sidebar -->
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>
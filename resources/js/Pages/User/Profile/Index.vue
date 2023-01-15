<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import SettingSideBar from '@/Components/SideBar/SettingSideBar.vue';
// Import Inertia
import { useForm,useAttrs, Head, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { computed } from 'vue';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        SettingSideBar,
        Inertia,
        computed,
        useForm,
        usePage,
        Head,
    },
    props: {
        user: String, default: () => ({}),
        can: Object, default: () => ({}),
        genders: Object, default: () => ({}),
        relationship_statuses: Object, default: () => ({}),
    },
    data() {
        return {
            image_url: null,
        }
    },
    setup(props) {
        // Set NULL
        if(props.user.first_name == null){
            props.user.first_name = '';
            props.user.last_name = '';
            props.user.birthday = '';
            props.user.gender = '';
            props.user.relationship_status = '';
            props.user.contact_number = '';
        }
        // Form
        const form = useForm({
            id: props.user.id,
            name: props.user.name,
            email: props.user.email,
            first_name: props.user.first_name,
            last_name: props.user.last_name,
            birthday: props.user.birthday,
            gender: props.user.gender,
            relationship_status: props.user.relationship_status,
            contact_number: props.user.contact_number,
            professional_status: 'none',
        });
        // Profile Store
        function submit() {
            Inertia.post(route('profile.store'), form)
        }
        return { form, submit };
    },
    methods: {
        // Preview Image
        previewImage(e) {
            const file = e.target.files[0];
            this.image_url = URL.createObjectURL(file);
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
        },
    },

    computed: {
        errors() {
            return usePage().props.value.errors;
        },
        success() {
            return usePage().props.value.success;
        },
    }
}
</script>


<template>
    <!-- Header -->
    <Head title="User Profile Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Profile</p>
                User
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
                    User Profile
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <SettingSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">

                        <!-- form submit -->
                        <form @submit.prevent="submit">
                            <div class="sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <!-- Profile -->
                                    <h1 class="text-xl font-bold text-indigo-500">User Profile</h1>
                                    
                                    <div v-if="errors">
                                        <div v-for="(error, key) in errors" :key="key">
                                            <div id="alert-2" class="flex p-4 mb-4 text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400" role="alert">
                                                <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                                <span class="sr-only">Info</span>
                                                <div class="ml-3 text-sm font-medium">
                                                    {{ error }}
                                                </div>
                                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="success">
                                        <div id="alert-3" class="flex p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-gray-800 dark:text-green-400" role="alert">
                                            <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                            <span class="sr-only">Info</span>
                                            <div class="ml-3 text-sm font-medium">
                                                {{ success }}
                                            </div>
                                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                                                <span class="sr-only">Close</span>
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-6 gap-6">
                                    
                                        <!-- First Name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-slate-600">First name</label>
                                            <input v-model="form.first_name" type="text" required name="first-name" id="first-name" autocomplete="given-name" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ First Name -->

                                        <!-- last name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="last-name" class="block text-sm font-medium text-slate-600">Last name</label>
                                            <input v-model="form.last_name" type="text" requried name="last-name" id="last-name" autocomplete="family-name" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ last name -->

                                        <!-- User Name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-slate-600">User Name</label>
                                            <input v-model="form.name" type="text" readonly name="name" id="name" autocomplete="name" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ User Name -->

                                        <!-- Contact Number -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="contact-number" class="block text-sm font-medium text-slate-600">Contact Number</label>
                                            <input v-model="form.contact_number" type="text" required name="contact-number" id="contact-number" autocomplete="contact_number" placeholder="+60" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Contact Number -->

                                        <!-- Email Address -->
                                        <div class="col-span-6 sm:col-span-4">
                                            <label for="email-address" class="block text-sm font-medium text-slate-600">Email address</label>
                                            <input v-model="form.email" type="text" readonly name="email-address" id="email-address" autocomplete="email" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Email Address -->

                                        <!-- Birth Date -->
                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="birth-date" class="block text-sm font-medium text-slate-600">Birth Date</label>
                                            <input v-model="form.birthday" type="date" required name="birth-date" id="birth-date" autocomplete="birth-date" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Birth Date -->

                                        <!-- Gender -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="gender" class="block text-sm font-medium text-slate-600">Gender</label>
                                            <select v-model="form.gender" required id="gender" name="gender" autocomplete="gender" class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="gender in genders" :key="gender" :value="gender">{{gender}}</option>
                                            </select>
                                        </div>
                                        <!--/ Gender -->

                                        <!-- Relationship Status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="relationship_status" class="block text-sm font-medium text-slate-600">Relationship Status</label>
                                            <select v-model="form.relationship_status" required id="relationship_status" name="relationship_status" autocomplete="relationship_status" class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="relationship_status in relationship_statuses" :key="relationship_status" :value="relationship_status">{{relationship_status}}</option>
                                            </select>
                                        </div>
                                        <!--/ Relationship Status -->
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="bg-white px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md fill-white border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save The Profile</span>
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

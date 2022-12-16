<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import SettingSideBar from '@/Components/SideBar/SettingSideBar.vue';
// Import Inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        SettingSideBar,
        Inertia,
        useForm,
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

<script>

// import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import SettingSideBar from '@/Components/SideBar/SettingSideBar.vue';

// import inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

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
        errors: Object,
        profile: String,
    },
    data() {
        return {
            image_url: null,
            genders: ['Male','Female','Transgender','Gender Neutral','Others'],
            relationship_statuses: ['Single','In a Relationship','Engaged','Married','It is Complicated','In an Open Relationship','Widowed','Separated','Divorced'],
        }
    },

    setup(props) {
        const form = useForm({
            name: null,
            email: null,
            first_name: null,
            last_name: null,
            birthday: null,
            gender: null,
            relationship_status: null,
            contact_number: null,

        });

        function submit() {
            this.form.description = props.description;
            Inertia.post(route('profile.update'), form)
        }

        return { form, submit };
    },
    methods: {
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

    <Head title="Profile" />
    <BreezeAuthenticatedLayout>

        <template #header>
            Profile
        </template>

        <template #content>

            <ContainerWithSideBar>

                <template #title>
                    Edit Your Profile
                </template>

                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>

                <template #tool>
                    <!-- professional side bar -->
                    <SettingSideBar>
                        <!-- null -->
                    </SettingSideBar>
                </template>

                <template #main>

                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- form submit -->
                        <form @submit.prevent="submit">
                            <div class="shadow sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                    
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-gray-700">First name</label>
                                            <input type="text" name="first-name" id="first-name"
                                                autocomplete="given-name"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="last-name" class="block text-sm font-medium text-gray-700">
                                                Last name</label>
                                            <input type="text" name="last-name" id="last-name"
                                                autocomplete="family-name"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="email-address"
                                                class="block text-sm font-medium text-gray-700">User Name</label>
                                            <input type="text" name="email-address" id="email-address"
                                                autocomplete="email"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="email-address"
                                                class="block text-sm font-medium text-gray-700">Contact Number</label>
                                            <input type="text" name="email-address" id="email-address"
                                                autocomplete="email"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-4">
                                            <label for="email-address"
                                                class="block text-sm font-medium text-gray-700">Email address</label>
                                            <input type="text" name="email-address" id="email-address"
                                                autocomplete="email"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="email-address"
                                                class="block text-sm font-medium text-gray-700">Birth Date</label>
                                            <input type="date" name="email-address" id="email-address"
                                                autocomplete="email"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="country"
                                                class="block text-sm font-medium text-gray-700">Gender</label>
                                            <select id="country" name="country" autocomplete="country-name"
                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="gender in genders">{{gender}}</option>
                                             
                                            </select>
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="country"
                                                class="block text-sm font-medium text-gray-700">Relationship Status</label>
                                            <select id="country" name="country" autocomplete="country-name"
                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="relationship_status in relationship_statuses">{{relationship_status}}</option>
                                            </select>
                                        </div>

                                        

                                    </div>
                                </div>
                                <!-- submit -->
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </template>

            </ContainerWithSideBar>
        </template>

    </BreezeAuthenticatedLayout>
</template>

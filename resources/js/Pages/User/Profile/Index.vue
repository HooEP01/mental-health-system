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
        user: String,
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
            id: props.user.id,
            name: props.user.name,
            email: props.user.email,
            first_name: props.user.first_name,
            last_name: props.user.last_name,
            birthday: props.user.birthday,
            gender: props.user.gender,
            relationship_status: props.user.relationship_status,
            contact_number: props.user.contact_number,

        });

        function submit() {
            Inertia.post(route('profile.store'), form)
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
                                    
                                        <!-- first name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-gray-700">First name</label>
                                            <input v-model="form.first_name" type="text" name="first-name" id="first-name" autocomplete="given-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- last name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                            <input v-model="form.last_name" type="text" name="last-name" id="last-name" autocomplete="family-name" requried class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- user name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-gray-700">User Name</label>
                                            <input v-model="form.name" type="text" name="name" id="name" autocomplete="name" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- contact number -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="contact-number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                            <input v-model="form.contact_number" type="text" name="contact-number" id="contact-number" autocomplete="contact_number" placeholder="+60" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- email address -->
                                        <div class="col-span-6 sm:col-span-4">
                                            <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                                            <input v-model="form.email" type="text" name="email-address" id="email-address" autocomplete="email" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- birth date -->
                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="birth-date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                                            <input v-model="form.birthday" type="date" name="birth-date" id="birth-date" autocomplete="birth-date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- gender -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                            <select v-model="form.gender" id="gender" name="gender" autocomplete="gender" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="gender in genders" :key="gender" :value="gender">{{gender}}</option>
                                            </select>
                                        </div>

                                        <!-- relationship status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="relationship_status" class="block text-sm font-medium text-gray-700">Relationship Status</label>
                                            <select v-model="form.relationship_status" id="relationship_status" name="relationship_status" autocomplete="relationship_status" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="relationship_status in relationship_statuses" :key="relationship_status" :value="relationship_status">{{relationship_status}}</option>
                                            </select>
                                        </div>

                                        
                                    </div>
                                </div>
                                <!-- submit -->
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save</button>
                                </div>
                            </div>
                        </form>
                        <!-- end form -->

                    </div>

                </template>

            </ContainerWithSideBar>

        </template>

    </BreezeAuthenticatedLayout>

</template>

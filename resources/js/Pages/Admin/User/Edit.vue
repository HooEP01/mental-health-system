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

    data() {
        return {
            professional_statuses:['pending', 'approved', 'disapproved'],
            genders: ['Male','Female','Transgender','Gender Neutral','Others'],
            relationship_statuses: ['Single','In a Relationship','Engaged','Married','It is Complicated','In an Open Relationship','Widowed','Separated','Divorced'],
        }
    },

    props: {
        errors: Object,
        user: Object,
        roles: Object,
    },

    setup(props) {
        const roles = props.roles;

        const form = useForm({
            id: props.user.id,
            role_name: props.user.role_name,
            old_role_name: props.user.role_name,
            professional_status: props.user.professional_status,
        });

        function submit() {
            Inertia.post(route('users_view.store'), form)
        }

        return { roles, form, submit };
    },
    methods: {
       
    },
}

</script>


<template>

    <Head title="Admin User Edit" />
    <BreezeAuthenticatedLayout>

        <template #header>
            Administrator
        </template>

        <template #content>

            <ContainerWithSideBar>

                <template #title>
                    Edit Users Information
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
                                            <input v-model="user.first_name" readonly type="text" name="first-name" id="first-name" autocomplete="given-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- last name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                            <input v-model="user.last_name" readonly type="text" name="last-name" id="last-name" autocomplete="family-name" requried class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- user name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-gray-700">User Name</label>
                                            <input v-model="user.name" type="text" name="name" id="name" autocomplete="name" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- contact number -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="contact-number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                            <input v-model="user.contact_number" readonly type="text" name="contact-number" id="contact-number" autocomplete="contact_number" placeholder="+60" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- email address -->
                                        <div class="col-span-6 sm:col-span-4">
                                            <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                                            <input v-model="user.email" type="text" name="email-address" id="email-address" autocomplete="email" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- birth date -->
                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="birth-date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                                            <input v-model="user.birthday" readonly type="date" name="birth-date" id="birth-date" autocomplete="birth-date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- gender -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                            <select v-model="user.gender" readonly id="gender" name="gender" autocomplete="gender" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="gender in genders" :key="gender" :value="gender">{{gender}}</option>
                                            </select>
                                        </div>

                                        <!-- relationship status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="relationship_status" readonly class="block text-sm font-medium text-gray-700">Relationship Status</label>
                                            <select v-model="user.relationship_status" id="relationship_status" name="relationship_status" autocomplete="relationship_status" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="relationship_status in relationship_statuses" :key="relationship_status" :value="relationship_status">{{relationship_status}}</option>
                                            </select>
                                        </div>

                                        
                                         <!-- professional title -->
                                         <div class="col-span-6">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-gray-700">Professional Title</label>
                                            <input v-model="user.professional_title" readonly type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- professional description -->
                                        <div class="col-span-6">
                                            <label for="about" class="block text-sm font-medium text-gray-700">Professional Description</label>
                                            <div class="mt-1">
                                                <textarea readonly v-model="user.professional_description" id="professional_description" name="professional_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="you@example.com"></textarea>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs are hyperlinked.</p>
                                        </div>

                                        <!-- image -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Photo</label>
                                            <div class="mt-1 flex items-center">
                                                <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                                <!-- if exist image url -->
                                                
                                                <img v-if="user.image" :src="'/storage/' + user.image" class="w-64 h-48 object-cover" />
                                                <svg v-else class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                </span>
                                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-slate-100 hover:bg-slate-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded ml-2">
                                                <span>Upload a Image</span>
                                                <input ref="photo" type="file" id="file-upload" name="file-upload" class="sr-only">
                                            </label>
                                                
                                            </div>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6">
                                            <p>Edit Users Status & Role</p>
                                        </div>

                                        <!-- professional status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="professional_status" class="block text-sm font-medium text-gray-700">Professional Status</label>
                                            <select v-model="form.professional_status" id="role_name" name="role_name" autocomplete="role_name" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="professional_status in professional_statuses" :key="professional_status" :value="professional_status">{{professional_status}}</option>
                                            </select>
                                        </div>

                                        <!-- role name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="role_name" class="block text-sm font-medium text-gray-700">Role</label>
                                            <select v-model="form.role_name" id="role_name" name="role_name" autocomplete="role_name" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="role in roles.data" :key="role.id" :value="role.name">{{role.name}}</option>
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

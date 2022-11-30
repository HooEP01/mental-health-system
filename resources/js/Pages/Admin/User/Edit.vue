<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import AdminSideBar from '@/Components/SideBar/AdminSideBar.vue';
// Import Inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        AdminSideBar,
        Inertia,
        useForm,
        Head,
    },
    props: {
        user: Object, default: () => ({}),
        roles: Object, default: () => ({}),
        professional_statuses: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
    setup(props) {
        // Role
        const roles = props.roles;
        // Form
        const form = useForm({
            id: props.user.id,
            role_name: props.user.role_name,
            old_role_name: props.user.role_name,
            professional_status: props.user.professional_status,
        });
        // User_view Store
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
    <!-- Header -->
    <Head title="Administrator User Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            Administrator
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Edit {{user.name}}'s Information
                </template>

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <AdminSideBar />
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
                                    
                                        <!-- first name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-slate-600">First name</label>
                                            <input v-model="user.first_name" readonly type="text" name="first-name" id="first-name" autocomplete="given-name" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- last name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="last-name" class="block text-sm font-medium text-slate-600">Last name</label>
                                            <input v-model="user.last_name" readonly type="text" name="last-name" id="last-name" autocomplete="family-name" requried class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- user name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-slate-600">User Name</label>
                                            <input v-model="user.name" type="text" name="name" id="name" autocomplete="name" readonly class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- contact number -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="contact-number" class="block text-sm font-medium text-slate-600">Contact Number</label>
                                            <input v-model="user.contact_number" readonly type="text" name="contact-number" id="contact-number" autocomplete="contact_number" placeholder="+60" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- email address -->
                                        <div class="col-span-6 sm:col-span-4">
                                            <label for="email-address" class="block text-sm font-medium text-slate-600">Email address</label>
                                            <input v-model="user.email" type="text" name="email-address" id="email-address" autocomplete="email" readonly class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- birth date -->
                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="birth-date" class="block text-sm font-medium text-slate-600">Birth Date</label>
                                            <input v-model="user.birthday" readonly type="date" name="birth-date" id="birth-date" autocomplete="birth-date" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- gender -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="gender" class="block text-sm font-medium text-slate-600">Gender</label>
                                            <input v-model="user.gender" readonly type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- relationship status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="relationship_status" readonly class="block text-sm font-medium text-slate-600">Relationship Status</label>
                                            <input v-model="user.relationship_status" readonly type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        
                                         <!-- professional title -->
                                         <div class="col-span-6">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-slate-600">Professional Title</label>
                                            <input v-model="user.professional_title" readonly type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- professional description -->
                                        <div class="col-span-6">
                                            <label for="about" class="block text-sm font-medium text-slate-600">Professional Description</label>
                                            <div class="mt-1">
                                                <textarea readonly v-model="user.professional_description" id="professional_description" name="professional_description" rows="3" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="you@example.com"></textarea>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs are hyperlinked.</p>
                                        </div>

                                        <!-- image -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-slate-600">Photo</label>
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

                                        <div class="col-span-6 sm:col-span-6 pt-6">
                                            <!-- Profile -->
                                            <h1 class="text-xl font-bold text-indigo-500">Edit User Role and Status</h1>
                                        </div>

                                        <!-- professional status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="professional_status" class="block text-sm font-medium text-slate-600">Professional Status</label>
                                            <select v-model="form.professional_status" id="role_name" name="role_name" autocomplete="role_name" required class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="professional_status in professional_statuses" :key="professional_status" :value="professional_status">{{professional_status}}</option>
                                            </select>
                                        </div>

                                        <!-- role name -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="role_name" class="block text-sm font-medium text-slate-600">Role</label>
                                            <select v-model="form.role_name" id="role_name" name="role_name" autocomplete="role_name" required class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="role in roles.data" :key="role.id" :value="role.name">{{role.name}}</option>
                                            </select>
                                        </div>


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
                        <!-- end form -->

                    </div>

                </template>

            </ContainerWithSideBar>

        </template>

    </BreezeAuthenticatedLayout>

</template>

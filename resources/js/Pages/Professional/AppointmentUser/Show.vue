<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import AdminSideBar from '@/Components/SideBar/AdminSideBar.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
// Import Inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        NavTabButton,
        AdminSideBar,
        NavTabBar,
        Inertia,
        useForm,
        Link,
        Head,
    },
    data() {
        return {
            tab: 'appointment',
        };
    },
    props: {
        user: Object, default: () => ({}),
        roles: Object, default: () => ({}),
        professional_statuses: Object, default: () => ({}),
        answers: Object, default: () => ({}),
        appointments: Object, default: () => ({}),
        appointment: Object, default: () => ({}),
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
        // Active Tab
        activeTab(name) {
            this.tab = name;
        },
        showTask(appointment_id, task_id){
            Inertia.get(route('appointments.tasks.show', [appointment_id, task_id]));
        },

        showAnswer(appointment_id, answer_id){
            Inertia.get(route('appointments.answers.show', [appointment_id, answer_id]));
        },

        back(value){
            let tab = 'member'
            Inertia.get(route('appointments.show', [value, {tab: tab}])); 
        },

        showAppointment(appointment_id){
            let tab = 'task'
            Inertia.get(route('appointments.show', [appointment_id, {tab: tab}]));
        }
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
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
                <p>Appointment: {{ appointment.event_title }}</p>
                <p class="text-base font-sm">
                    User {{ user.name }}
                </p>
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link @click="back(appointment.id)">
                    <NavTabButton class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='arrow-back'></box-icon>
                        <span class="inline-block align-top"> Back </span>
                    </NavTabButton>
                    </Link>
                </li>
                <!--/ Back Tab -->

                <!-- Content Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('appointment')" :active="tab === 'appointment'"
                        class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-heart'></box-icon>
                        <span class="inline-block align-top">Appointment</span>
                    </NavTabButton>
                </li>
                <!--/ Content Tab -->

                <!-- Schedule Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('answer')" :active="tab === 'answer'"
                        class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Answer</span>
                    </NavTabButton>
                </li>
                <!--/ Schedule Tab -->

                <li class="mr-6">
                    <NavTabButton @click="activeTab('setting')" :active="tab === 'setting'"
                        class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Setting</span>
                    </NavTabButton>
                </li>

            </NavTabBar>
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
       
            <div class="px-4 sm:px-0 md:col-span-4 md:mt-0 mt-5 gap-y-10 gap-x-6 xl:gap-x-3">
                <div v-if="tab === 'appointment'">
                    <div class="">
                        <div v-for="appoint in appointments" :key="appoint.id" class="grid gap-5 grid-cols-12 group relative sm:overflow-hidden sm:rounded-md">
                            <div class="mt-3 mb-3 col-span-3">
                                <Link @click="showAppointment(appoint.id)">
                                    <div  class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                        <img v-if="appoint.event_image" :src="'/storage/' +appoint.event_image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                        <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                    </div>
                                    <div class="mt-4 flex justify-between p-2 bg-white">
                                        <div>
                                            <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                                {{ appoint.event_title }}
                                            </h3>
                                            <ul class="list-disc pt-2">
                                                <li class="flow-root">
                                                    <p class="inline-flex items-center text-left w-full fill-black bg-white text-gray-800  font-base text-sm border border-transparent rounded">
                                                        <span class="inline-block align-top text-sm font-semibold">Start At {{ appoint.start_date }} {{ appoint.start_time}}</span>
                                                    </p>
                                                </li>
                                            </ul>
                                            <p class="mt-1 text-sm text-gray-600">By {{ appoint.professional_title }} {{ appoint.first_name }} {{ appoint.last_name }} | {{ appoint.status }}</p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                                
                            <div class="mt-3 mb-3 col-span-9">
                                <div  class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                                    <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                        <div v-for="(task, index) of appoint.task" :key="task.id">
                                            <div v-if="task.category == 'User'" class="bg-violet-50 sm:overflow-hidden sm:rounded-md">
                                                <div class="flex justify-between">
                                                    <div class="px-6 py-6 font-bold">
                                                        {{ index + 1 }}. {{ task.title }}
                                                    </div> 
                                                </div>
                                                <div class="flex flex-col items-left px-6 pb-6">
                                                    <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                                    <p>{{ task.content_title }}</p>
                                                    <div class="flex mt-2 space-x-3 md:mt-2">
                                                        <div v-if="task.content_answer_id">
                                                            <Link @click="showAnswer(appoint.id, task.content_answer_id)"  class="inline-flex items-center text-left w-50 fill-white hover:text-white hover:bg-violet-600 hover:fill-white text-white bg-violet-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                                <span class="inline-block align-top">Answer </span>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="bg-emerald-50 sm:overflow-hidden sm:rounded-md">
                                                <div class="flex justify-between">
                                                    <div class="px-6 py-6 font-bold">
                                                        {{ index + 1 }}. {{ task.title }}
                                                    </div> 
                                                </div>
                                                <div class="flex flex-col items-left px-6 pb-6">
                                                    <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                                    <p>{{ task.content_title }}</p>
                                                    <div class="flex mt-2 space-x-3 md:mt-2">
                                                        <div v-if="task.content_answer_id">
                                                            <Link @click="showAnswer(appoint.id, task.content_answer_id)"  class="inline-flex items-center text-left w-50 fill-white hover:text-white hover:bg-emerald-600 hover:fill-white text-white bg-emerald-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                                <span class="inline-block align-top">Answer </span>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="tab === 'answer'" class="px-4 sm:px-0 md:col-span-4 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-4 xl:gap-x-4">
                    <div v-for="content in answers.data" :key="content.id" class="group relative sm:overflow-hidden sm:rounded-md">
                        <Link :href="route('answers.show', content.id)">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                <img v-if="content.image" :src="'/storage/' + content.image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between p-2 bg-white">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                        {{ content.title }}
                                    </h3>
                                    <ul class="list-disc">
                                        <li class="flow-root">
                                            <p class="inline-flex items-center text-left w-full fill-black bg-white text-gray-800  font-base text-sm border border-transparent rounded">
                                            <span class="inline-block align-top font-semibold text-sm">{{ content.updated_at }}</span>
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <div v-if="tab === 'setting'">
                    <div class="sm:overflow-hidden sm:rounded-md">
                        <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                            <!-- Profile -->
                            <h1 class="text-xl font-bold text-indigo-500">User Profile</h1>
                            <div class="grid grid-cols-6 gap-6">
                                <!-- first name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="first-name" class="block text-sm font-medium text-slate-600">First name</label>
                                    <input  v-model="user.first_name" readonly type="text" name="first-name" id="first-name" autocomplete="given-name" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <!-- last name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="last-name" class="block text-sm font-medium text-slate-600">Last name</label>
                                    <input v-model="user.last_name" readonly type="text" name="last-name" id="last-name" autocomplete="family-name" requried class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <!-- user name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-slate-600">User Name</label>
                                    <input v-model="user.name" type="text" name="name" id="name" autocomplete="name" readonly  class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                                    <input v-model="user.birthday" readonly type="date" name="birth-date" id="birth-date" autocomplete="birth-date" required  class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                                    <label for="first-name" class="block text-sm font-medium text-slate-600">Professional Title</label>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>

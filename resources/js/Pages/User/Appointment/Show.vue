<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
// Import inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        DropdownLink,
        NavTabButton,
        NavTabBar,
        Dropdown,
        Inertia,
        useForm,
        Head,
        Link,
        ref,
    },
    data(props) {
        return {
            tab: props.firstTab,
        };
    },
    props: {
        event: Object, default: () => ({}),
        appointment: Object, default:() => ({}),
        appointments: Object, default:() => ({}),
        tasks: Object, default:() => ({}),
        professional: Object, default:() => ({}),
        firstTab: Object, default:() => ({}),
        can: Object, default: () => ({}),
    },
    setup(props) {
        // Answer
        const answers = ref({});
        // Form
        const form = useForm({
            answer_id: null,
            event_id: props.event.id,
            schedules: [],
            scheduleDateTime: [],
            answers: null,
        });
        // Answers Store
        function submit() {
            this.form.answers = answers;
            Inertia.post(route('appointment.store'), form)
        }
        return { answers, form, submit };
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('appointment.destroy', id));
        },
        // Active Tab
        activeTab(name) {
            this.tab = name;
        },

        showTask(appointment_id, task_id){
            Inertia.get(route('appointment.task.show', [appointment_id, task_id]));
        },

        showAnswer(appointment_id, answer_id){
            Inertia.get(route('appointment.answer.show', [appointment_id, answer_id]));
        },


    }
}
</script>


<style setup>
.prose {
    max-width: none;
}
</style>
    

<template>
    <!-- Header -->
    <Head title="User Appointment Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Journey</p>
                Appointment: {{ event.title }}
                <p class="text-base font-sm">
                    {{ appointment.start_date }} {{ appointment.start_time }}
                </p>
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link :href="route('appointment.index')">
                        <NavTabButton class="inline-block p-4 rounded-t-lg border-b-2"> 
                                <box-icon class='mr-2' name='arrow-back'></box-icon>
                                <span class="inline-block align-top"> Back </span>
                        </NavTabButton>
                    </Link>
                </li>
                <!--/ Back Tab -->

                <!-- Content Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('event')" :active="tab === 'event'" class="inline-block p-4 rounded-t-lg border-b-2"> 
                        <box-icon class='mr-2' name='book-heart'></box-icon>
                        <span class="inline-block align-top">{{ event.category }}</span>
                    </NavTabButton>
                </li>
                <!--/ Content Tab -->

                <!-- Task Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('task')" :active="tab === 'task'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Task</span>
                    </NavTabButton>
                </li>
                <!--/ Task Tab -->

                <!-- Member Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('member')" :active="tab === 'member'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Member</span>
                    </NavTabButton>
                </li>
                <!--/ Member Tab -->


            </NavTabBar>
            <!--/ NavTabBar -->      
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Show {{ event.category }}
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                    <div class="pt-4">
                        <div v-if="event.image" class="sm:overflow-hidden sm:rounded-md">
                            <img :src="'/storage/' + event.image" class="w-199 h-100 object-cover" />
                        </div>
                    </div>
                </template>
                <!--/ #Subtitle -->
                    
                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                                         
                        <!-- Content Show Card -->
                        <div v-if="tab === 'event'" class="sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <div v-html="event.description" class="prose w-full text-slate-600"></div>
                            </div>
                        </div>
                        <!--/ Content Show Card -->


                        <div v-if="tab === 'task'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <!-- Form -->
                                <h1 class="text-3xl font-bold">Task</h1>

                                <div v-for="(task, index) of tasks" :key="task.id">
                                    <div class="bg-violet-50 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                {{ index + 1 }}. {{ task.title }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-left px-6 pb-6">
                                            <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                            <div class="flex mt-2 space-x-3 md:mt-2">
                                                <Link @click="showTask(appointment.id, task.id)"  class="inline-flex items-center text-left w-50 fill-white hover:text-white hover:bg-violet-600 hover:fill-white text-white bg-violet-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                    <span class="inline-block align-top">{{ task.content_title }}</span>
                                                </Link>
                                            </div>
                                                <div v-if="task.answer.length" class="grid grid-cols-12 mt-2 gap-2">
                                                    <div v-for="(answer, index) of task.answer" class="col-span-3 sm:col-span-2">
                                                        <div v-if="answer.status == 'Answered'">
                                                            <Link @click="showAnswer(appointment.id, answer.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-emerald-600 hover:fill-white text-white bg-emerald-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                                <span class="inline-block align-top">Ans: {{ index + 1 }}</span>
                                                            </Link>
                                                        </div>
                                                        <div v-else-if="answer.status == 'Commented'">
                                                            <Link @click="showAnswer(appointment.id, answer.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-emerald-700 hover:fill-white text-white bg-emerald-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                                <span class="inline-block align-top">Ans: {{ index + 1 }}</span>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div v-if="tab === 'member'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl font-bold">Owner</h1>
                                <div class="bg-yellow-50 sm:overflow-hidden sm:rounded-md">
                                    <div class="flex justify-between">
                                        <div class="px-6 py-6 font-bold">
                                            {{ 1 }}. {{ professional.professional_title }} {{ professional.first_name }} {{ professional.last_name }}
                                        </div>
                                        <div></div>
                                        <Dropdown class="flex justify-end px-4 pt-4">
                                            <template #trigger>
                                                <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                                                    <span class="sr-only">Open dropdown</span>
                                                    <box-icon name='dots-horizontal-rounded'></box-icon>
                                                </button>
                                            </template>

                                            <template #content>
                                                <ul class="py-1" aria-labelledby="dropdownButton">
                                                    <li>
                                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                                    </li>
                                                </ul>
                                            </template>
                                        </Dropdown>
                                    </div>
                                    <div class="flex flex-col items-left px-6 pb-6">
                                        <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                            <img v-if="professional.image" :src="'/storage/' + professional.image" class="w-64 h-48 object-cover" />
                                            <svg v-else class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                        <p class="text-base font-medium text-slate-600 mt-3">{{ professional.professional_description }}</p>
                
                                    </div>
                                </div>
                                <h1 class="text-3xl font-bold">Member</h1>
                                <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
                                    <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                        <!-- Table -->
                                        <table class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
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
                                                <tr v-for="(appoint, index) in appointments" class="bg-white border-b border-gray-400">
                                                    <td data-label="Title" class="py-4 px-6">
                                                        {{ index + 1 }}
                                                    </td>
                                                    <td data-label="Title" class="py-4 px-6">
                                                        {{ appoint.name }}
                                                    </td>
                                                    <td data-label="User_id" class="py-4 px-6">
                                                        {{ appoint.first_name }} {{ appoint.last_name }}
                                                    </td>
                                                    <td data-label="Description" class="py-4 px-6">
                                                        {{ appoint.email }}
                                                    </td>
                                                    <td v-if="appoint.id === appointment.id">
                                                        <Link @click="destroy(appointment.id)" class="inline-flex items-center text-left fill-white text-white w-30 bg-red-400 hover:bg-red-500 font-semibold py-2 px-4 border border-transparent rounded">
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
                        </div>

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
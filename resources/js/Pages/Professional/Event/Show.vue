<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import ScheduleViewer from "@/Components/Editor/ScheduleViewer.vue";
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
        ScheduleViewer,
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
    props: {
        event: Object, default: () => ({}),
        schedules: Object, default:() => ({}),
        tasks: Object, default:() => ({}),
        professional: Object, default:() => ({}),
        appointments: Object, default:() => ({}), 
        tasks_professional: Object, default:() => ({}), 
        firstTab: Object, default:() => ({}), 
        can: Object, default: () => ({}),
    },
    data(props) {
        return {
            tab: props.firstTab,
        };
    },
    setup(props) {
        // Form
        const form = useForm({
            event_id: props.event.id,
            schedules: [],
            scheduleDateTime: [],
        });

        return { form };
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('events.destroy', id));
        },
        // Destroy task
        destroyTask(event_id, task_id) {
            Inertia.delete(route('events.tasks.destroy', [event_id, task_id]));
        },
        // Active Tab
        activeTab(name) {
            this.tab = name;
        },

        createTask(id) {
            Inertia.get(route('events.tasks.create', id));
        },

        showTask(event_id, task_id){
            Inertia.get(route('events.tasks.show', [event_id, task_id]));
        }
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
    <Head title="Professional Event Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
                Event: {{ event.title }}
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link :href="route('events.index')">
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

                <!-- Schedule Tab -->
                <li class="mr-6" v-if="schedules.length">
                    <NavTabButton @click="activeTab('appointment')" :active="tab === 'appointment'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='calendar'></box-icon>
                        <span class="inline-block align-top">Schedule</span>
                    </NavTabButton>
                </li>
                <!--/ Schedule Tab -->

                <!-- Task Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('task')" :active="tab === 'task'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Task</span>
                    </NavTabButton>
                </li>
                <!--/ Task Tab -->

                <!-- Report Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('report')" :active="tab === 'report'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='report' type='solid' ></box-icon>
                        <span class="inline-block align-top">Report</span>
                    </NavTabButton>
                </li>
                <!--/ Report Tab -->

                <!-- Member Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('member')" :active="tab === 'member'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='cog'></box-icon>
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
                        <ul class="list-disc pt-4">
                            <li class="flow-root">
                                <p class="inline-flex items-center text-left w-full fill-white bg-indigo-400 text-white font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class='mr-2' name='cube'></box-icon> 
                                    <span class="inline-block align-top text-base">Status {{ event.status }}</span>
                                </p>
                            </li>
                        </ul>
                    </div>
                </template>
                <!--/ #Subtitle -->

                <!-- #Feature -->
                <template #feature>
                    <!-- edit content -->
                    <li class="flow-root">
                        <Link v-if="can.edit" :href="route('events.edit', event.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-edit'></box-icon>
                        <span class="inline-block align-top">Edit This Event</span>
                        </Link>
                    </li>
                    <!-- destroy content -->
                    <li class="flow-root">
                        <Link v-if="can.delete" @click="destroy(event.id)"
                            class="inline-flex items-center text-left w-full fill-black hover:fill-white hover:text-white hover:bg-red-500 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-minus'></box-icon>
                        <span class="inline-block align-top">Delete This Event</span>
                        </Link>
                    </li>
                </template>
                <!--/ #Feature -->

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

                        <!-- Event Schedule Show Card -->
                        <div v-if="schedules.length && tab === 'appointment'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <!-- Question Viewer -->
                            <div  class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl font-bold">Schedule</h1>
                                <ScheduleViewer v-model="form.scheduleDateTime" :schedules="schedules" :events="event" :appointments="appointments" :index="index" />
                            </div>
                            <!--/ Question Viewer -->                  
                        </div>
                        <!--/ Event Schedule Show Card -->

                        <div v-if="tab === 'task'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl font-bold">Task</h1>

                                <div class="col-span-6 sm:col-span-3">
                                    <button type="button" @click="createTask(event.id)" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-violet-500 hover:bg-violet-600">
                                        <box-icon class="mr-2" name='message-square-add'></box-icon>
                                        <span class="inline-block align-top text-base mr-2">Create Event Task</span>
                                    </button>
                                </div>

                                <div v-for="task of tasks" :key="task.id">
                                    <div class="bg-violet-50 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                {{ task.title }}
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
                                                        <a :href="route('events.tasks.edit', [event.id, task.id])" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a @click="destroyTask(event.id, task.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                                    </li>
                                                    </ul>
                                                </template>
                                            </Dropdown>
                                        </div>
                                        <div class="flex flex-col items-left px-6 pb-6">
                                            <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                            <div class="flex mt-2 space-x-3 md:mt-2">
                                                <Link @click="showTask(event.id, task.id)"  class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-violet-500 hover:fill-white text-white bg-violet-400 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                    <span class="inline-block align-top">{{ task.content_title }}</span>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="tab === 'report'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl font-bold">Report</h1>
                                <div class="col-span-6 sm:col-span-3">
                                    <button type="button"  @click="createTask(event.id)" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-teal-500 hover:bg-teal-600">
                                        <box-icon class="mr-2" name='message-square-add'></box-icon>
                                        <span class="inline-block align-top text-base mr-2">Create Event Task</span>
                                    </button>
                                </div>
                                <div v-for="(task, index) of tasks_professional" :key="tasks_professional.id">
                                    <div class="bg-teal-50 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                {{ task.title }}
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
                                                            <a :href="route('events.tasks.edit', [event.id, task.id])" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a @click="destroyTask(event.id, task.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                                        </li>
                                                    </ul>
                                                </template>
                                            </Dropdown>
                                        </div>
                                        <div class="flex flex-col items-left px-6 pb-6">
                                            <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                            <div class="flex mt-2 space-x-3 md:mt-2">
                                                <Link @click="showTask(event.id, task.id)"  class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-teal-500 hover:fill-white text-white bg-teal-400 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                    <span class="inline-block align-top">{{ task.content_title }}</span>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div v-if="tab === 'member'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl font-bold">Owner</h1>
                                <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md">
                                    <div class="flex justify-between">
                                        <div class="px-6 py-6 font-bold">
                                            {{ 1 }}. {{ professional.professional_title }} {{ professional.first_name }} {{ professional.last_name }}
                                        </div>
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
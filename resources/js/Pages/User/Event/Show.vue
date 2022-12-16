<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ScheduleViewer from "@/Components/Editor/ScheduleViewer.vue";
import NavTabButton from '@/Components/NavTabButton.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
// Import inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
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
    data() {
        return {
            tab: 'event',
        };
    },
    props: {
        event: Object, default: () => ({}),
        schedules: Object, default:() => ({}),
        appointments: Object, default:() => ({}),
        tasks: Object, default:() => ({}),
        professional: Object, default:() => ({}),
        can: Object, default: () => ({}),
    },
    setup(props) {
        // Answer
        const answers = ref({});
        // Form
        const form = useForm({
            event_id: props.event.id,
            appointment_id: null,
            reason: null,
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
        // Active Tab
        activeTab(name) {
            this.tab = name;
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
    <Head title="User Event Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Resource</p>
                Event: {{ event.title }}
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link :href="route('event.index')">
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
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Appointment</span>
                    </NavTabButton>
                </li>
                <!--/ Schedule Tab -->

                <li class="mr-6" v-if="tasks.length">
                    <NavTabButton @click="activeTab('task')" :active="tab === 'task'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Task</span>
                    </NavTabButton>
                </li>

                <li class="mr-6">
                    <NavTabButton @click="activeTab('member')" :active="tab === 'member'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Member</span>
                    </NavTabButton>
                </li>


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

                <!-- #Feature -->
                <!-- <template #feature> -->
                    <!-- edit content -->
                    <!-- <li class="flow-root">
                        <Link v-if="can.edit" :href="route('event.edit', event.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-edit'></box-icon>
                        <span class="inline-block align-top">Edit This Event</span>
                        </Link>
                    </li> -->
                <!-- </template> -->
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
                            <!-- Form -->
                            <form @submit.prevent="submit" class="container mx-auto">

                                <!-- Question Viewer -->
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-3xl font-bold">Select Time</h1>
                                    <ScheduleViewer v-model="form.scheduleDateTime" :schedules="schedules" :events="event"  :appointments="appointments" :index="index" />
                                </div>
                                <!--/ Question Viewer -->

                                <!-- Question Viewer -->
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <h1 class="text-3xl font-bold">Appointment</h1>
                                        <!-- Appointment Description -->
                                        <div class="col-span-6">
                                            <label for="about" class="block text-sm font-medium text-gray-700">Description</label>
                                            <div class="mt-1">
                                                <textarea v-model="form.reason" id="professional_description" name="professional_description" rows="3" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder=""></textarea>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Brief description for your problem.</p>
                                        </div>
                                        <!--/ Appointment Description -->

                                        <!-- Appointment Price -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="professional_status" class="block text-sm font-medium text-slate-600">Price (MYR)</label>
                                            <input v-model="event.price" readonly required type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Appointment Price -->

                                        

                                    </div>
                                </div>
                                <!--/ Question Viewer -->


                                <!-- Submit -->
                                <div class="bg-white px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center fill-white rounded-md border border-transparent bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save Your Appointment</span>
                                    </button>
                                </div>
                                <!--/ Submit -->
                            </form>
                            <!--/ Form -->
                        </div>
                        <!--/ Event Schedule Show Card -->
                       
                        <div v-if="tab === 'task'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl font-bold">Task</h1>

                                <div v-for="(task, index) of tasks" :key="task.id">
                                    <div class="bg-violet-50 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                {{ index + 1 }}. {{ task.title }}
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
                                            <p v-html="task.description" class="text-base font-medium text-slate-600 line-clamp-3"></p>
                                            <div class="flex mt-2 space-x-3 md:mt-2">
                                                <Link  :href="route('content.show', task.content_id)" class="inline-flex items-center text-left w-50 fill-white hover:text-white hover:bg-violet-600 hover:fill-white text-white bg-violet-500 font-semibold py-3 px-4 border border-transparent rounded">
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
                                        <!-- <p class="mt-3">Task To Complete</p>
                                        <div class="flex mt-2 space-x-3 md:mt-2">
                                            <Link @click="showTask(event.id, task.id)"  class="inline-flex items-center text-left w-50 fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                <span class="inline-block align-top">{{ task.content_title }}</span>
                                            </Link>
                                        </div> -->
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
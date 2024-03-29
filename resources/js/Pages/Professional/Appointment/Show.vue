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
        appointments: Object, default:() => ({}),
        appointment: Object, default:() => ({}),
        tasks_professional: Object, default:() => ({}),
        tasks: Object, default:() => ({}),
        professional: Object, default:() => ({}),
        firstTab: Object, default:() => ({}), 
        can: Object, default: () => ({}),
        appointmentsID: Object, default: () => ({}),
        chats: Object, default: () => ({}),
    },
    setup(props) {
        // Form
        const chatForm = useForm({
            appointment_id: props.appointment.id,
            chat_id: null,
            message: null,
        });
        // Edit Chat
        function editChat(chat) {
            this.chatForm.chat_id = chat.id;
            this.chatForm.message = chat.message;
        };
        // Cancel chat
        function resetChat() {
            chatForm.reset();
        };
        // Chats Store
        function submit() {
            Inertia.post(route('appointments.chats.store', chatForm.appointment_id), chatForm);
            chatForm.reset();
        };
        return { chatForm, editChat, resetChat, submit };
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('events.destroy', id));
        },
        // Destroy task
        destroyTask(appointment_id, task_id) {
            Inertia.delete(route('appointments.tasks.destroy', [appointment_id, task_id]));
        },
        // Active Tab
        activeTab(name) {
            this.tab = name;
        },

        createTask(id) {
            Inertia.get(route('appointments.tasks.create', id));
        },

        showTask(appointment_id, task_id){
            Inertia.get(route('appointments.tasks.show', [appointment_id, task_id]));
        },

        showAnswer(appointment_id, answer_id){
            Inertia.get(route('appointments.answers.show', [appointment_id, answer_id]));
        },

        destroyChat(appointment_id, chat_id) {
            Inertia.delete(route('appointments.chats.destroy', [appointment_id, chat_id]));
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
    <Head title="Professional Event Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
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
                    <Link :href="route('appointments.index')">
                        <NavTabButton class="inline-block p-4 rounded-t-lg border-b-2"> 
                            <box-icon class='mr-2' name='arrow-back'></box-icon>
                            <span class="inline-block align-top">Back</span>
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

                <!-- Report Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('report')" :active="tab === 'report'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='report' type='solid' ></box-icon>
                        <span class="inline-block align-top">Report</span>
                    </NavTabButton>
                </li>
                <!--/ Report Tab -->

                <!-- Chat Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('chat')" :active="tab === 'chat'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='message-square-dots'></box-icon>
                        <span class="inline-block align-top">Chat</span>
                    </NavTabButton>
                </li>
                <!--/ Chat Tab -->

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
                                    <span class="inline-block align-top text-base">{{ appointment.start_date }} {{ appointment.start_time }}</span>
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
                                         
                        <div v-if="tab === 'event'" class="sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-0 py-0 sm:p-6">
                                <div v-html="event.description" class="prose w-full text-slate-600"></div>
                            </div>
                        </div>

                        <div v-if="tab === 'task'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-0 py-0 sm:p-6">
                                <h1 class="text-3xl font-bold">Task</h1>
                                <div class="col-span-6 sm:col-span-3">
                                    <button type="button"  @click="createTask(appointment.id)" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-violet-500 hover:bg-violet-600">
                                        <box-icon class="mr-2" name='message-square-add'></box-icon>
                                        <span class="inline-block align-top text-base mr-2">Create Appointment Task</span>
                                    </button>
                                </div>
                                <div v-for="(task, index) of tasks" :key="task.id">
                                    <div class="bg-violet-50 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                {{ index + 1 }}. {{ task.title }}
                                            </div>
                                            <div></div>
                                            <Dropdown v-if="task.appointment_id != null" class="flex justify-end px-4 pt-4">
                                                <template #trigger>
                                                    <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                                                        <span class="sr-only">Open dropdown</span>
                                                        <box-icon name='dots-horizontal-rounded'></box-icon>
                                                    </button>
                                                </template>

                                                <template #content>
                                                    <ul class="py-1" aria-labelledby="dropdownButton">
                                                        <li><a :href="route('appointments.tasks.edit', [task.appointment_id, task.id])" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a></li>
                                                        <li><a @click="destroyTask(task.appointment_id, task.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a></li>
                                                    </ul>
                                                </template>
                                            </Dropdown>
                                        </div>
                                        <div class="flex flex-col items-left px-6 pb-6">
                                            <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                            <div class="flex mt-2 space-x-3 md:mt-2">
                                                <Link @click="showTask(appointment.id, task.id)"  class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-violet-500 hover:fill-white text-white bg-violet-400 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                    <span class="inline-block align-top">{{ task.content_title }}</span>
                                                </Link>
                                            </div>
                                            <div v-if="task.answer.length" class="grid grid-cols-12 mt-2 gap-2">
                                                <div v-for="(answer, index) of task.answer" class="col-span-4 sm:col-span-4">
                                                    <div>
                                                        <Link @click="showAnswer(answer.appointment_id, answer.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-violet-600 hover:fill-white text-white bg-violet-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                            <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                            <span class="inline-block align-top">{{ index + 1 }}. {{ answer.user_name }}</span>
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div v-if="tab === 'report'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-0 py-0 sm:p-6">
                                <h1 class="text-3xl font-bold">Report</h1>
                                <div class="col-span-6 sm:col-span-3">
                                    <button type="button"  @click="createTask(appointment.id)" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-teal-500 hover:bg-teal-600">
                                        <box-icon class="mr-2" name='message-square-add'></box-icon>
                                        <span class="inline-block align-top text-base mr-2">Create Appointment Task</span>
                                    </button>
                                </div>
                                <div v-for="(task, index) of tasks_professional" :key="task.id">
                                    <div class="bg-teal-50 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                {{ index + 1 }}. {{ task.title }}
                                            </div>
                                            <div></div>
                                            <Dropdown v-if="task.appointment_id != null" class="flex justify-end px-4 pt-4">
                                                <template #trigger>
                                                    <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                                                        <span class="sr-only">Open dropdown</span>
                                                        <box-icon name='dots-horizontal-rounded'></box-icon>
                                                    </button>
                                                </template>

                                                <template #content>
                                                    <ul class="py-1" aria-labelledby="dropdownButton">
                                                        <li><a :href="route('appointments.tasks.edit', [task.appointment_id, task.id])" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a></li>
                                                        <li><a @click="destroyTask(task.appointment_id, task.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a></li>
                                                    </ul>
                                                </template>
                                            </Dropdown>
                                        </div>
                                        <div class="flex flex-col items-left px-6 pb-6">
                                            <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                                            <p>{{ task.content_title }}</p>
                                            <div class="grid grid-cols-12 mt-2 gap-2">
                                                <div v-for="appoint in appointments" :key="appointment.id" class="col-span-4 sm:col-span-4">
                                                    <Link @click="showTask(appoint.id, task.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-teal-500 hover:fill-white text-white bg-teal-400 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                        <span class="inline-block align-top"> {{ appoint.name }}</span>
                                                    </Link>
                                                </div>
                                            </div>
                                            <div v-if="task.answer.length" class="grid grid-cols-12 mt-2 gap-2">
                                                <div v-for="(answer, index) of task.answer" class="col-span-4 sm:col-span-4">
                                                    <div>
                                                        <Link @click="showAnswer(answer.appointment_id, answer.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-teal-600 hover:fill-white text-white bg-teal-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                            <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                                            <span class="inline-block align-top">{{ index + 1 }}. {{ answer.user_name }}</span>
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="tab === 'chat'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <form @submit.prevent="submit">
                                <div class="space-y-6 bg-white px-0 py-0 sm:p-6">
                                    <h1 class="text-3xl font-bold">Chat</h1>
                                    <div class="bg-white border border-slate-400 sm:overflow-hidden sm:rounded-md">
                                        <div class="flex justify-between">
                                            <div class="px-6 py-6 font-bold">
                                                Message
                                            </div>                                   
                                        </div>
                                        <div class="flex flex-col px-6 pb-6">

                                            <div v-for="chat in chats" :key="chat.id" class="">
                                                <div v-if="chat.user_id != professional.id">
                                                    <div v-if="chat.status != 'Deleted'">
                                                        <p class="text-base font-medium text-slate-600 mt-3 text-right">{{ chat.message }}</p>
                                                        <p class="text-sm font-base text-slate-400 text-right">{{ chat.updated_at }} {{ chat.status }} </p>
                                                        <p class="text-sm font-base text-slate-400 text-right">By {{ chat.name }} </p>
                                                    </div>
                                                    <div v-else>
                                                        <p class="text-base font-medium text-slate-400 mt-3 text-right">This message has been {{ chat.status.toLowerCase() }}</p>
                                                        <p class="text-sm font-base text-slate-400 text-right">By {{ chat.name }} </p>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <div v-if="chat.status != 'Deleted'" class="flex justify-between">
                                                        <div>
                                                            <p class="text-base font-medium text-slate-600 mt-3">{{ chat.message }}</p>
                                                            <p class="text-sm font-base text-slate-400">{{ chat.updated_at }} {{ chat.status }}</p>
                                                        </div>
                                        
                                                        <Dropdown class="flex justify-end">
                                                            <template #trigger>
                                                                <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                                                                    <span class="sr-only">Open dropdown</span>
                                                                    <box-icon name='dots-horizontal-rounded'></box-icon>
                                                                </button>
                                                            </template>
                                                            <template #content>
                                                                <ul class="py-1" aria-labelledby="dropdownButton">
                                                                    <li><a @click="editChat(chat)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a></li>
                                                                    <li><a @click="destroyChat(appointment.id, chat.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a></li>
                                                                </ul>
                                                            </template>
                                                        </Dropdown>
                                                    </div>
                                                    <div v-else>
                                                        <p class="text-base font-medium text-slate-400 mt-3">You {{ chat.status.toLowerCase() }} this message</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="flex px-6 pb-6">
                                            <label for="title" class="pr-4 pt-2 flex-none w-16 text-sm font-medium text-slate-600">Message</label>
                                            <input v-model="chatForm.message" type="text" name="title" id="title" autocomplete="title" required class="flex-initial block w-full rounded-md border-gray-400 mr-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <button v-if="chatForm.chat_id" @click="resetChat()" type="button" class="flex-none pt-1 w-14 mr-2 justify-center rounded-md border border-transparent fill-white bg-red-500 text-sm font-medium text-white shadow-sm hover:bg-red-600">
                                                <box-icon name='message-square-x'></box-icon>
                                            </button>
                                            <button type="submit" class="flex-none pt-1 w-14 justify-center rounded-md border border-transparent fill-white bg-indigo-500 text-sm font-medium text-white shadow-sm hover:bg-indigo-600">
                                                <box-icon name='send'></box-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                                    <th scope="col" class="py-3 px-6">Reason</th>
                                                    <th scope="col" class="py-3 px-6">View</th>
                                                    <th scope="col" class="py-3 px-6">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr v-for="(appoint, index) in appointments" :key="appointment.id"  class="bg-white border-b border-gray-400">
                
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
                                                        <p class="mt-1 text-sm text-gray-500 line-clamp-5">{{ appoint.reason }}</p>
                                                    </td>
                                         
                                                    <td data-label="View" class="py-4 px-6">
                                                        <Link :href="route('appointments.users.show', [appoint.id, appoint.user_id])"
                                                            class="inline-flex items-center text-left fill-white text-white w-full bg-yellow-400 hover:bg-yellow-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                            <box-icon class='mr-1' name='show-alt'></box-icon>
                                                            <span class="mr-1 inline-block align-top">View</span>
                                                        </Link>
                                                    </td>
                                                    
                                                    <td data-label="View" class="py-4 px-6">
                                                        <Link v-if="appoint.status != 'Approve'" :href="route('appointments.edit', appoint.id)"
                                                            class="inline-flex items-center text-left fill-white text-white w-full bg-indigo-500 hover:bg-indigo-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                            <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                            <span class="inline-block align-top">Approve</span>
                                                        </Link>
                                                        <Link v-else :href="route('appointments.edit', appoint.id)"
                                                            class="inline-flex items-center text-left fill-white text-white w-full bg-red-500 hover:bg-red-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                            <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                            <span class="inline-block align-top">Ban</span>
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
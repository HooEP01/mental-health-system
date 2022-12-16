<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import ContentTaskEditor from "@/Components/Editor/ContentTaskEditor.vue";
import Tiptap from '@/Components/Tiptap.vue'
// Import Inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Import Uuid
import { v4 as uuidv4 } from "uuid";
// Option API

export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        ContentTaskEditor,
        Tiptap,
        Inertia,
        useForm,
        Link,
        Head,
    },
    props: {
        event: Object,
        appointment: Object,
        contents: Object,
        categories: Object,
        statuses: Object,
        formats: Object,
        tiptapDescription: String,
        
    },
    setup(props) {

        let theContent = ref({});
        let addContent = ref(false);

        // Form
        const form = useForm({
            appointment_id: props.appointment.id,
            event_id: props.event.id,
            task_id: null,
            content_id: null,
            title: null,
            category: null,
            description: null,
        });
        // Contents Store 
        function submit() {
            this.form.description = props.tiptapDescription;
            if(this.addContent){
                this.form.content_id = this.theContent.id;
            }
            Inertia.post(route('appointments.tasks.store', form.appointment_id), form);
        };

        return { form, addContent, theContent, submit };

    },
    methods: {
        addContentButton(){
            (this.addContent === true)? this.addContent = false :  this.addContent = true;
        },
        back(value){
            let tab = 'task';
            if(this.form.category == 'Professional') {
                tab = 'report'
            }
            Inertia.get(route('appointments.show', [value, {tab: tab}])); 
        }
    }
}

</script>


<template>
    <!-- Header -->
    <Head title="Professional Appointment Task Create" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
           <!-- Title Header -->
           <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
                Appointment: {{ event.title }}
            </div>
            <!--/ Title Header -->
        </template>

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Create New Task
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
                    <li class="flow-root">
                        <Link @click="back(appointment.id)" class="inline-flex items-center text-left w-full fill-white bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 border border-transparent rounded">
                            <box-icon class='mr-2' name='arrow-back'></box-icon> <span class="inline-block align-top">Back To Appointment</span>
                        </Link>
                    </li>
                </template>
                <!--/ #Feature -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- Form -->
                        <form @submit.prevent="submit">
                            <div class="sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-xl font-bold text-indigo-500">Create Appointment Task</h1>

                                    <!-- Title -->
                                    <div class="col-span-6">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Title</label>
                                        <input v-model="form.title" type="text" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->

                                    <!-- Category -->
                                    <div class="col-span-6">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Category</label>
                                        <select v-model="form.category" id="category" name="category" autocomplete="category-name" required class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                            <option v-for="category in categories" :key="category" :value="category">{{category.toUpperCase()}}</option>
                                        </select>
                                    </div>
                                    <!--/ Category -->

                                    <!-- Description -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-slate-600">Description</label>
                                        <div class="mt-1">
                                            <!-- tiptap text editor -->
                                            <Tiptap v-model="tiptapDescription" id="tiptapDescription" name="tiptapDescription" class="h-64"/>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Description for your post. URLs are hyperlinked.</p>
                                    </div>
                                    <!--/ Description -->

                                    <!-- Content -->
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-slate-600">Content</label>
                                        <div class="mt-2">
                                            <!-- add new question button -->
                                            <button @click="addContentButton" type="button" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600">
                                                <box-icon class="mr-2" name='message-square-add'></box-icon>
                                                <span class="inline-block align-top text-base mr-2">Add Content</span>
                                            </button>

                                            <div v-if="addContent" class="mt-4">
                                                <input v-model="theContent.title" type="text" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>                                            
                                                <ContentTaskEditor v-model="theContent" :contents="contents" :event="event" :index="index" :appointment="appointment"/>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Content -->

                                    <!-- Submit -->
                                    <div class="px-4 py-3 text-right">
                                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent fill-white bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600">
                                            <box-icon class='mr-2' name='cube'></box-icon> 
                                            <span class="inline-block align-top text-base mr-2">Save The Appointment Task</span>
                                        </button>
                                    </div>
                                    <!--/ Submit -->

                                </div>
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





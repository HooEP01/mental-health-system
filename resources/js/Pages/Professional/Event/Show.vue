<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import ScheduleViewer from "@/Components/Editor/ScheduleViewer.vue";
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
        Inertia,
        useForm,
        Head,
        Link,
        ref,
    },
    props: {
        event: Object, default: () => ({}),
        schedules: Object, default:() => ({}),
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
            Inertia.post(route('appointments.store'), form)
        }
        return { answers, form, submit };
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('events.destroy', id));
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
            Professional
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Show {{ event.category }} Event
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
                                <p class="inline-flex items-center text-left w-full fill-white bg-yellow-400 text-white font-semibold py-3 px-4 border border-transparent rounded">
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

                <!-- #Tool -->
                <template #tool>
                    <ProfessionalSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- Content Show Card -->
                        <div class="sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl text-slate-900 font-bold">{{ event.title }}</h1>
                                <div v-html="event.description" class="prose w-full text-slate-600"></div>
                            </div>
                        </div>
                        <!--/ Content Show Card -->

                        <!-- Event Schedule Show Card -->
                        <div v-if="schedules.length" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <!-- Form -->
                            <form @submit.prevent="submit" class="container mx-auto">

                                <!-- Question Viewer -->
                                <div  class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-3xl font-bold">Schedule</h1>
                                    <ScheduleViewer v-model="form.scheduleDateTime" :schedules="schedules" :index="ind" />
                                </div>
                                <!--/ Question Viewer -->

                                <!-- Submit -->
                                <div class="bg-white px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center fill-white rounded-md border border-transparent bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save Your Answer</span>
                                    </button>
                                </div>
                                <!--/ Submit -->
                            </form>
                            <!--/ Form -->
                        </div>
                        <!--/ Event Schedule Show Card -->
                       


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
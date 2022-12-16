<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import ScheduleEditor from "@/Components/Editor/ScheduleEditor.vue";
import Tiptap from '@/Components/Tiptap.vue'
// Import Inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Import Uuid
import { v4 as uuidv4 } from "uuid";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        ScheduleEditor,
        Tiptap,
        Inertia,
        useForm,
        Link,
        Head,
    },
    props: {
        categories: Object,
        statuses: Object,
        tiptapDescription: String,
        attendance_individual: Object,
        event: Object,
        schedules: Object,
    },
    data() {
        return {
            image_url: null,
        }
    },
    setup(props) {
        // Form
        const form = useForm({
            event_id: props.event.id,
            title: props.event.title,
            image: props.event.image,
            category: props.event.category,
            status: props.event.status,
            attendance: props.event.attendance,
            price: props.event.price,
            session_length: props.event.session_length,
            description: props.event.description,
            schedules: props.schedules,
        });
        // Contents Store 
        function submit() {
            Inertia.post(route('events.store'), form)
        }
        return { form, submit };
    },
    methods: {
        // Preview Image
        previewImage(e) {
            const file = e.target.files[0];
            this.image_url = URL.createObjectURL(file);
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
        },
        // Create Schedule
        addSchedule(index) {
            // schedule
            const newSchedule = {
                id: uuidv4(),
                event_id: null,
                category: "present",
                periodical: "",
                start_datetime: "",
                end_datetime: "",
                recurring: 1,
                data: {},
            };
            // locate it at the bottom of old questions
            this.form.schedules.splice(index, 0, newSchedule);
        },
        // Destroy Schedule
        deleteSchedule(schedule) {
            this.form.schedules = this.form.schedules.filter((q) => q !== schedule);
        },
        // Retain Options Value 
        scheduleChange(schedule) {
            if (schedule.data.options) {
                schedule.data.options = [...schedule.data.options];
            }
            this.form.schedules = this.form.schedules.map((q) => {
                if (q.id === schedule.id) {
                    return JSON.parse(JSON.stringify(schedule));
                }
                return q;
            });
        },
        //schedule sort
        scheduleSort() {
            (this.form.schedules).sort(function sortByDate(a, b) {

                if (a.start_datetime < b.start_datetime)
                    return -1
                
                if (a.start_datetime > b.start_datetime)
                    return 1

                return 0;
            })

            return this.form.schedules;
        }
    }
}
</script>


<template>
    <!-- Header -->
    <Head title="Professional Event Create" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
           <!-- Title Header -->
           <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
                Event
            </div>
            <!--/ Title Header -->
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Create New Event
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Feature -->
                <template #feature>
                    <li class="flow-root">
                        <Link :href="route('events.index')" class="inline-flex items-center text-left w-full fill-white bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 border border-transparent rounded">
                            <box-icon class='mr-2' name='arrow-back'></box-icon> <span class="inline-block align-top">Back To Home</span>
                        </Link>
                    </li>
                </template>
                <!--/ #Feature -->

                <!-- #Tool -->
                <!-- <template #tool>
                    <ProfessionalSideBar/>
                </template> -->
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- Form -->
                        <form @submit.prevent="submit">
                            <div class="sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-xl font-bold text-indigo-500">Create Event</h1>
                                    
                                    <!-- Title -->
                                    <div class="col-span-6">
                                        <label for="title" class="block text-sm font-medium text-slate-600">Title</label>
                                        <input v-model="form.title" type="text" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <!--/ Title -->

                                    <!-- Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-600">Cover Image</label>
                                        <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-400 px-6 pt-5 pb-6">
                                            <div class="space-y-1 text-center">
                                                <!-- if exist image url -->
                                                
                                                <img v-if="image_url" :src="image_url" :alt="form.title"
                                                    class="w-64 h-48 object-cover" />
                                                    <img v-else-if="form.image" :src="'/storage/' + form.image" class="w-64 h-48 object-cover" />
                                                <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                                        <span>Upload a Image</span>
                                                        <input @change="previewImage" ref="photo" type="file" id="file-upload" name="file-upload" class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Image -->
                                    
                                    <!-- Select -->
                                    <div class="grid grid-cols-6 gap-6">
                                        <!-- Category -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="category"
                                            class="block text-sm font-medium text-slate-600">Category</label>
                                            <select v-model="form.category" id="category" name="category" autocomplete="category-name" required class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="category in categories" :key="category" :value="category">{{category.toUpperCase()}}</option>
                                            </select>
                                        </div>
                                        <!--/ Category -->

                                        <!-- Status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="status" class="block text-sm font-medium text-slate-600">Status</label>
                                            <select v-model="form.status" id="status" name="status" autocomplete="status-name" required class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="status in statuses" :key="status" :value="status">{{status.toUpperCase()}}</option>
                                            </select>
                                        </div>
                                        <!--/ Status -->

                                        <!-- Attendance -->
                                        <div v-if="form.category != 'Individual'" class="col-span-6 sm:col-span-3">
                                            <label for="status" class="block text-sm font-medium text-slate-600">Attendance Per Session</label>
                                            <input v-model="form.attendance" type="text" name="attendance" id="attendance" autocomplete="title" placeholder="10" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Attendance -->

                                        <!-- Session Length -->
                                        <div v-if="form.category != 'Seminar'" class="col-span-6 sm:col-span-3">
                                            <label for="status" class="block text-sm font-medium text-slate-600">Session Length (Minutes)</label>
                                            <input v-model="form.session_length" type="text" name="price" id="price" autocomplete="title" required placeholder="60" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Session Length -->

                                        <!-- Price -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="status" class="block text-sm font-medium text-slate-600">Price</label>
                                            <input v-model="form.price" type="text" name="price" id="price" autocomplete="title" required placeholder="MYR" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Price -->


                                    </div>
                                    <!--/ Select -->

                                    <!-- Description -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-slate-600">Description</label>
                                        <div class="mt-1">
                                            <!-- tiptap text editor -->
                                            <Tiptap v-model="form.description" id="tiptapDescription" name="tiptapDescription" class="h-64"/>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Description for your post. URLs are hyperlinked.</p>
                                    </div>
                                    <!--/ Description -->

                                    <!-- Question -->
                                    <h1 class="text-xl font-bold text-indigo-500">Create Schedules</h1>
                                    <div>
                                        <div class="mt-1">
                                            <!-- add new question button -->
                                            <button type="button" @click="addSchedule()" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600">
                                                <box-icon class="mr-2" name='message-square-add'></box-icon>
                                                <span class="inline-block align-top text-base mr-2">Create Schedule</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- show all questions -->
                                    <div v-for="(schedule, index) in scheduleSort()" :key="schedule.id">
                                        <!-- question editor -->
                                        <ScheduleEditor :schedule="schedule" :index="index" @change="scheduleChange" @addSchedule="addSchedule" @deleteSchedule="deleteSchedule" />
                                    </div>
                                    <!--/ Question -->
                                </div>
                                
                                <!-- No question -->
                                <div v-if="!form.schedules.length" class="px-4 py-3 text-left sm:px-6">
                                    You don't have any schedules created
                                </div>
                                <!--/ No question -->

                                <!-- Submit -->
                                <div class="px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent fill-white bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save The Event</span>
                                    </button>
                                </div>
                                <!--/ Submit -->
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
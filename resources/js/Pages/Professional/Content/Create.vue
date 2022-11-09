<script>

// import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import QuestionEditor from "@/Components/editor/QuestionEditor.vue";
import Tiptap from '@/Components/Tiptap.vue'

// import inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

// import uuid
import { v4 as uuidv4 } from "uuid";

export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        QuestionEditor,
        Tiptap,
        Inertia,
        useForm,
        Head,
    },

    props: {
        errors: Object,
        // receive description data from Tiptap
        description: String,
    },

    data() {
        return {
            categories: ["Article", "Case Study", "Survey", "Journal", "Report"],
            statuses: ["draft","complete"],
            image_url: null,
        }
    },

    setup(props) {
        // form
        const form = useForm({
            id: null,
            title: null,
            image: null,
            slug: null,
            category: '1',
            status: '1',
            description: null,
            questions: [],
        });

        // store function
        function submit() {
            this.form.description = props.description;
            Inertia.post(route('contents.store'), form)
        }

        return { form, submit };
    },

    methods: {

        // preview image
        previewImage(e) {
            const file = e.target.files[0];
            this.image_url = URL.createObjectURL(file);
            // use ref with the name photo
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
        },

        // create question
        addQuestion(index) {

            // question
            const newQuestion = {
                id: uuidv4(),
                content_id: null,
                category: "text",
                question: "",
                description: null,
                data: {},
            };

            // locate it at the bottom of old questions
            this.form.questions.splice(index, 0, newQuestion);
        },

        // destroy question
        deleteQuestion(question) {
            this.form.questions = this.form.questions.filter((q) => q !== question);
        },

        // retain options value 
        questionChange(question) {
            // Important to explicitelly assign question.data.options, because otherwise it is a Proxy object
            // and it is lost in JSON.stringify()
            if (question.data.options) {
                question.data.options = [...question.data.options];
            }
            this.form.questions = this.form.questions.map((q) => {
                if (q.id === question.id) {
                    return JSON.parse(JSON.stringify(question));
                }
                return q;
            });
        },

    }

}

</script>


<template>

    <!-- header  -->
    <Head title="Content Create" />

    <!-- breeze authenticated layout -->
    <BreezeAuthenticatedLayout>
        
        <template #header>
            Professional
        </template>

        <template #content>

            <!-- container with sidebar -->
            <ContainerWithSideBar>

                <template #title>
                    Create Your Content
                </template>

                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>

                <template #tool>
                    <!-- professional side bar -->
                    <ProfessionalSideBar>
                        <!-- null -->
                    </ProfessionalSideBar>

                </template>

                <template #main>

                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- form -->
                        <form @submit.prevent="submit">
                            <div class="shadow sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">

                                    <!-- title -->
                                    <div class="col-span-6">
                                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                        <input v-model="form.title" type="text" name="title" id="title" autocomplete="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- profile image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                                        <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                            <div class="space-y-1 text-center">
                                                <!-- if exist image url -->
                                                <img v-if="image_url" :src="image_url" :alt="form.title"
                                                    class="w-64 h-48 object-cover" />
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

                                    <!-- select -->
                                    <div class="grid grid-cols-6 gap-6">
                                         <!-- category -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="category"
                                                class="block text-sm font-medium text-gray-700">Type</label>
                                            <select  v-model="form.category" id="category" name="category" autocomplete="category-name" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="category in categories" :key="category" :value="category">{{category}}</option>
                                            </select>
                                        </div>
                                        <!-- status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select v-model="form.status" id="status" name="status" autocomplete="status-name" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="status in statuses" :key="status" :value="status">{{status}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <div class="mt-1">
                                            <!-- Tiptap text editor-->
                                            <Tiptap v-model="description" id="description" name="description" class="h-64" />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Description for your post. URLs are hyperlinked.</p>
                                    </div>

                                    <!-- Question -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Questions</label>
                                        <div class="mt-1">
                                            <!-- Add new question -->
                                            <button type="button" @click="addQuestion()" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 text-sm font-medium text-white shadow-sm focus:ring-offset-2 bg-green-500 hover:bg-green-600">
                                                Add New Question
                                            </button>
                                        </div>
                                    </div>
                                    <!-- show all questions -->
                                    <div v-for="(question, index) in form.questions" :key="question.id">
                                        <!-- Question editor -->
                                        <QuestionEditor :question="question" :index="index" @change="questionChange" @addQuestion="addQuestion" @deleteQuestion="deleteQuestion" />
                                    </div>
                                </div>

                                <!-- if no question -->
                                <div v-if="!form.questions.length" class="bg-gray-50 px-4 py-3 text-left sm:px-6">
                                    You don't have any questions created
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
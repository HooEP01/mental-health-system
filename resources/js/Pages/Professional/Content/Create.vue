<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import QuestionEditor from "@/Components/Editor/QuestionEditor.vue";
import QuestionFormatEditor from "@/Components/Editor/QuestionFormatEditor.vue";
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
        QuestionFormatEditor,
        ContainerWithSideBar,
        ProfessionalSideBar,
        QuestionEditor,
        Tiptap,
        Inertia,
        useForm,
        Head,
        Link,
    },
    props: {
        categories: Object, default: () => ({}),
        statuses: Object, default: () => ({}),
        formats: Object, default: () => ({}),
        tiptapDescription: String, default: () => ({}),
    },
    data() {
        return {
            image_url: null,
            format_boolean: false,
        }
    },
    setup(props) {
        // Form
        const form = useForm({
            content_id: null,
            title: null,
            image: null,
            slug: null,
            category: null,
            status: null,
            description: null,
            questions: [],
            format_category: null, 
            formats: [],
        });
        // Contents Store 
        function submit() {
            this.form.description = props.tiptapDescription;
            Inertia.post(route('contents.store'), form)
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

        changeFormatCategory() {
            if(this.form.format_category === "Unit") {
                this.format_boolean = true;
            }else{
                this.format_boolean = false;
            }
        },

        // Create format
        addFormat(index) {
            // question format
            const newFormat = {
                id: uuidv4(),
                unit: null,
                description: null,
                data: {},
            };

            // locate it at the bottom of old formats
            this.form.formats.splice(index, 0, newFormat);
        },
        // Destroy Question
        deleteFormat(format) {
            this.form.formats = this.form.formats.filter((q) => q !== format);
        },
        // Retain Options Value 
        formatChange(format) {
            // Important to explicitelly assign question.data.options, because otherwise it is a Proxy object
            // and it is lost in JSON.stringify()
            if (format.data.options) {
                format.data.options = [...format.data.options];
            }
            this.form.formats = this.form.formats.map((q) => {
                if (q.id === format.id) {
                    return JSON.parse(JSON.stringify(format));
                }
                return q;
            });
        },

        // Create Question
        addQuestion(index) {
            // question
            const newQuestion = {
                id: uuidv4(),
                index: 0,
                content_id: null,
                category: "text",
                question: "",
                description: null,
                data: {},
            };
            // locate it at the bottom of old questions
            this.form.questions.splice(index, 0, newQuestion);
            let count = 0;
            for(let i = 0; i < this.form.questions.length; i++){
                this.form.questions[i].index = count;
                console.log(this.form.questions[i]);
                count++;
            }
        },
        // Destroy Question
        deleteQuestion(question) {
            this.form.questions = this.form.questions.filter((q) => q !== question);
        },
        // Retain Options Value 
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
    <!-- Header -->
    <Head title="Professional Content Create" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
                Content
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
                    Create New Content
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
                        <Link :href="route('contents.index')" class="inline-flex items-center text-left w-full fill-white bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 border border-transparent rounded">
                            <box-icon class='mr-2' name='arrow-back'></box-icon> <span class="inline-block align-top">Back To Home</span>
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
                                    <!-- Name  -->
                                    <h1 class="text-xl font-bold text-indigo-500">Create New Content</h1>
                                    
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
                                    </div>
                                    <!--/ Select -->

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

                                    <!-- Question -->
                                    <h1 class="text-xl font-bold text-indigo-500">Create Format</h1>
                                    
                                    <!-- Format -->
                                    <div>
                                        <div class="mt-1">
                                            <div class="grid grid-cols-6 gap-6">
                                                <!-- Format Category -->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <select v-model="form.format_category" @change="changeFormatCategory()" id="format" name="format" autocomplete="format-name" required class="mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                        <option v-for="format in formats" :key="format" :value="format">{{format.toUpperCase()}}</option>
                                                    </select>
                                                </div>
                                                <!--/ Format Category -->

                                                <div v-if="format_boolean" class="col-span-6 sm:col-span-3">
                                                    <div class="text-right">
                                                        <button type="button" @click="addFormat()" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600">
                                                            <box-icon class="mr-2" name='message-square-add'></box-icon>
                                                            <span class="inline-block align-top text-base mr-2">Create Format</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Format -->

                                    <!-- Question Format Editor -->
                                    <div v-for="(format, index) in form.formats" :key="format.id">
                                        <QuestionFormatEditor v-if="format_boolean" :format="format" :index="index" @change="formatChange" @addFormat="addFormat" @deleteFormat="deleteFormat"/>
                                    </div>
                                    <!--/ Question Format Editor -->

                                    <!-- Question -->
                                    <h1 class="text-xl font-bold text-indigo-500">Create Question</h1>

                                    <!-- Question -->
                                    <div>
                                        <div class="mt-1">
                                            <div class="grid grid-cols-6 gap-6">
                                                <!-- Format Category -->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <button type="button"  @click="addQuestion()" class="inline-flex justify-center rounded-md border border-transparent py-2 px-4 fill-white text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600">
                                                            <box-icon class="mr-2" name='message-square-add'></box-icon>
                                                            <span class="inline-block align-top text-base mr-2">Create Question</span>
                                                    </button>
                                                </div>
                                                <!--/ Format Category -->

                                                <div class="col-span-6 sm:col-span-3">
                                                    <div class="text-right mt-5">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Question -->


                                    <!-- show all questions -->
                                    <div v-for="(question, index) in form.questions" :key="question.id">
                                        <!-- question editor -->
                                        <QuestionEditor :formats="form.formats" :question="question" :index="index" @change="questionChange" @addQuestion="addQuestion" @deleteQuestion="deleteQuestion" />
                                    </div>
                                    <!--/ Question -->
                                </div>

                                <!-- Submit -->
                                <div class="px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent fill-white bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save The Content</span>
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
<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
import QuestionViewer from "@/Components/Editor/QuestionViewer.vue";
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
        NavTabButton,
        QuestionViewer,
        Inertia,
        useForm,
        Head,
        Link,
        ref,
    },
    data() {
        return {
            tab: 'content',
        };
    },
    props: {
        content: Object, default: () => ({}),
        questions: Object, default:() => ({}),
        can: Object, default: () => ({}),
    },
    setup(props) {
        // Answer
        const answers = ref({});
        // Form
        const form = useForm({
            answer_id: null,
            content_id: props.content.id,
            questions: [],
            answers: null,
        });
        // Answers Store
        function submit() {
            this.form.answers = answers;
            Inertia.post(route('answers.store'), form)
        }
        return { answers, form, submit };
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('contents.destroy', id));
        },
        // Active Tab
        activeTab(name) {
            this.tab = name;
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
    <Head title="Professional Content Show" />
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
                    Show {{ content.category }}
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                    <div class="pt-4">
                        <div v-if="content.image" class="sm:overflow-hidden sm:rounded-md">
                            <img :src="'/storage/' + content.image" class="w-199 h-100 object-cover" />
                        </div>
                        <ul class="list-disc pt-4">
                            <li class="flow-root">
                                <p class="inline-flex items-center text-left w-full fill-white bg-yellow-400 text-white font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class='mr-2' name='cube'></box-icon> 
                                    <span class="inline-block align-top text-base">Status {{ content.status }}</span>
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
                        <Link v-if="can.edit" :href="route('contents.edit', content.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-edit'></box-icon>
                        <span class="inline-block align-top">Edit This Content</span>
                        </Link>
                    </li>
                    <!-- destroy content -->
                    <li class="flow-root">
                        <Link v-if="can.delete" @click="destroy(content.id)"
                            class="inline-flex items-center text-left w-full fill-black hover:fill-white hover:text-white hover:bg-red-500 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-minus'></box-icon>
                        <span class="inline-block align-top">Delete This Content</span>
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
                        
                        <div v-if="questions.length" class="mb-4 border-b border-gray-200 dark:border-gray-700">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                                <li class="mr-2">
                                    <NavTabButton @click="activeTab('content')" :active="tab === 'content'" class="inline-block p-4 rounded-t-lg border-b-2"> 
                                        <box-icon class='mr-2' name='book-heart'></box-icon>
                                        <span class="inline-block align-top">{{ content.category }}</span>
                                    </NavTabButton>
                                </li>
                                <li class="mr-2" >
                                    <NavTabButton @click="activeTab('question')" :active="tab === 'question'" class="inline-block p-4 rounded-t-lg border-b-2">
                                        <box-icon class='mr-2' name='book-add'></box-icon>
                                        <span class="inline-block align-top">Question</span>
                                    </NavTabButton>
                                </li>
                            </ul>
                        </div>

                        <!-- Content Show Card -->
                        <div v-if="tab === 'content'" class="sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1 class="text-3xl text-slate-900 font-bold">{{ content.title }}</h1>
                                <div v-html="content.description" class="prose w-full text-slate-600"></div>
                            </div>
                        </div>
                        <!--/ Content Show Card -->

                        <!-- Content Question Show Card -->
                        <div v-if="questions.length && tab === 'question'" class="sm:overflow-hidden sm:rounded-md">
                            <!-- Form -->
                            <form @submit.prevent="submit" class="container mx-auto">

                                <!-- Question Viewer -->
                                <div  class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-3xl font-bold">Question</h1>
                                    <div v-for="(question, ind) of questions" :key="question.id">
                                        <QuestionViewer v-model="answers[question.id]" :question="question" :index="ind" />
                                    </div>
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
                        <!--/ Content Question Show Card -->
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
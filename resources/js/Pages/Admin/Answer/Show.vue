<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import QuestionFormatViewer from "@/Components/Editor/QuestionFormatViewer.vue";
import QuestionViewer from "@/Components/Editor/QuestionViewer.vue";
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
// Import Inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        QuestionFormatViewer,
        ContainerWithSideBar,
        QuestionViewer,
        NavTabButton,
        NavTabBar,
        Inertia,
        useForm,
        Head,
        Link,
        ref,
    },
    props: {
        content: Object, default: () => ({}),
        questions: Object, default: () => ({}),
        answer: Object, default: () => ({}),
        question_answer: Object, default: () => ({}),
        can: Object, default: () => ({}),
        question_answers_array: Object, default: () => ({}),
    },
    data() {
        return {
            tab: 'content',
        };
    },
    setup(props) {
        // Answers
        const theAnswers = JSON.parse(JSON.stringify(props.question_answers_array));
        // Form
        const form = useForm({
            answer_id: props.answer.id,
            content_id: props.content.id,
            questions: [],
            answers: null,
        });
        // Answer Store
        function submit() {
            this.form.answers = JSON.parse(JSON.stringify(theAnswers));
            Inertia.post(route('answers_view.store'), form)
        }
        return { theAnswers, form, submit };
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('answers.destroy', id));
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
    <Head title="Professional Answer Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Administrator</p>
                Answer: {{ content.title }}
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link :href="route('answers_view.index')">
                        <NavTabButton class="inline-block p-4 rounded-t-lg border-b-2"> 
                                <box-icon class='mr-2' name='arrow-back'></box-icon>
                                <span class="inline-block align-top"> Back </span>
                        </NavTabButton>
                    </Link>
                </li>
                <!--/ Back Tab -->

                <!-- Content Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('content')" :active="tab === 'content'" class="inline-block p-4 rounded-t-lg border-b-2"> 
                        <box-icon class='mr-2' name='book-heart'></box-icon>
                        <span class="inline-block align-top">{{ content.category }}</span>
                    </NavTabButton>
                </li>
                <!--/ Content Tab -->

                <!-- Question Tab -->
                <li class="mr-6" v-if="questions.length">
                    <NavTabButton @click="activeTab('question')" :active="tab === 'question'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Question</span>
                    </NavTabButton>
                </li>
                <!--/ Question Tab -->

                <li class="mr-2" v-if="content.format_category === 'Unit'">
                    <NavTabButton @click="activeTab('format')" :active="tab === 'format'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Result</span>
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
                                <p class="inline-flex items-center text-left w-full fill-white bg-indigo-400 text-white font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class='mr-2' type='solid' name='edit'></box-icon>
                                    <span class="inline-block align-top text-base">{{ answer.updated_at }}</span>
                                </p>
                            </li>
                        </ul>
                    </div>
                </template>

                <!-- #Feature -->
                <template #feature>
                    <!-- edit content -->
                    <li class="flow-root">
                        <Link v-if="can.edit" :href="route('contents.show', content.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-edit'></box-icon>
                        <span class="inline-block align-top">Create New Answer</span>
                        </Link>
                    </li>
                    <!-- destroy content -->
                    <li class="flow-root">
                        <Link v-if="can.delete" @click="destroy(answer.id)"
                            class="inline-flex items-center text-left w-full fill-black hover:fill-white hover:text-white hover:bg-red-500 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-minus'></box-icon>
                        <span class="inline-block align-top">Delete This Answer</span>
                        </Link>
                    </li>
                </template>
                <!--/ #Feature -->

                <!-- #Tool -->
                <!-- <template #tool>
                    <ProfessionalSideBar />
                </template> -->
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">

                        <!-- Content Show Card -->
                        <div v-if="tab === 'content'" class="sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <div v-html="content.description" class="prose w-full text-slate-600"></div>
                            </div>
                        </div>
                        <!--/ Content Show Card -->

                        <!-- Content Question Show Card -->
                        <div v-if="questions.length && tab === 'question'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <!-- Form -->
                            <form @submit.prevent="submit" class="container mx-auto">

                                <!-- Question Viewer -->
                                <div  class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <h1 class="text-3xl font-bold">Question</h1>
                                    <div v-for="(question, ind) of questions" :key="question.id">
                                        <QuestionViewer v-model="theAnswers[question.id]" :question="question" :index="ind" />
                                    </div>
                                </div>
                                <!--/ Question Viewer -->

                                <!-- Submit -->
                                <!-- <div class="bg-white px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center fill-white rounded-md border border-transparent bg-indigo-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Update Your Answer</span>
                                    </button>
                                </div> -->
                                <!--/ Submit -->
                            </form>
                            <!--/ Form -->
                        </div>
                        <!--/ Content Question Show Card -->

                        <!-- Content Format Result Show Card -->
                        <div v-if="questions.length && content.format_category === 'Unit' && tab === 'format'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white sm:p-6">
                                <!-- Question Viewer -->
                                <h1 class="text-3xl font-bold">Result</h1>
                                <QuestionFormatViewer :answers="question_answer" :content="content" />
                                <!--/ Question Viewer -->
                            </div>
                        </div>
                        <!-- Content Format Result Show Card -->

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
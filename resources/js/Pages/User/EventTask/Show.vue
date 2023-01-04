<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
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
        NavTabButton,
        QuestionViewer,
        NavTabBar,
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
    setup() {
        // Answer
        const answers = ref({});
       
        return { answers };
    },
    props: {
        task: Object, default: () => ({}),
        event: Object, default: () => ({}),
        content: Object, default: () => ({}),
        questions: Object, default:() => ({}),
        can: Object, default: () => ({}),
    },
    methods: {
        // Active Tab
        activeTab(name) {
            this.tab = name;
        },
        back(value){
            Inertia.get(route('event.show', [value, {tab: 'task'}])); 
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

            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">{{ event.title }}</p>
                Task: {{ content.title }}
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link @click="back(event.id)">
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
                    </div>
                </template>
                <!--/ #Subtitle -->

        
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
                        <div v-if="questions.length && tab === 'question'" class="sm:overflow-hidden sm:rounded-md">
                            <!-- Form -->
                            <form class="container mx-auto">
                                <!-- Question Viewer -->
                                <div  class="space-y-6 bg-white sm:p-6">
                                    <h1 class="text-3xl font-bold">Question</h1>
                                    <div v-for="(question, ind) of questions" :key="question.id">
                                        <QuestionViewer v-model="answers[question.id]" :question="question" :index="ind" />
                                    </div>
                                </div>
                                <!--/ Question Viewer -->
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
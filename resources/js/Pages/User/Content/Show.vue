<script>

// import layout file
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ResourceSideBar from '@/Components/SideBar/ResourceSideBar.vue';
import QuestionViewer from "@/Components/editor/QuestionViewer.vue";

// import inertia file
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";


export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ResourceSideBar,
        QuestionViewer,
        Inertia,
        useForm,
        Head,
        Link,
        ref,
    },

    props: {
        content: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },

    setup(props) {
        
        const answers = ref({});

        // form
        const form = useForm({
            id: null,
            content_id: props.content.id,
            questions: [],
            answers: null,
        });

        // store function
        function submit() {
            this.form.answers = answers;
            Inertia.post(route('answer.store'), form)
        }

        return { answers, form, submit };
    },

}

</script>


<style setup>
.prose {
    max-width: none;
}
</style>
    

<template>

    <!-- header -->

    <Head title="Content Show" />

    <!-- breeze authenticated layout -->
    <BreezeAuthenticatedLayout>

        <template #header>
            Professional
        </template>

        <template #content>
            <!-- side navigation bar -->
            <ContainerWithSideBar>

                <template #title>
                    {{ content.category }}
                </template>

                <template #subtitle>
                    
                    <img v-if="content.image" :src="'/storage/' + content.image" class="w-199 h-100 object-cover" />

                </template>

                <template #tool>
                    <!-- professional side bar -->
                    <ResourceSideBar>
                        <!-- null -->
                    </ResourceSideBar>

                </template>

                <template #main>

                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- content -->
                        <div class="shadow sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                
                                <h1>{{ content.title }}</h1>
                                <div v-html="content.description" class="prose w-full"></div>
                            </div>
                        </div>

                        <!-- content question -->
                        <div class="shadow sm:overflow-hidden sm:rounded-md mt-4">

                            <!-- form -->
                            <form @submit.prevent="submit" class="container mx-auto">

                                <!-- question viewer -->
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <p>Question</p>
                                    <div v-for="(question, ind) of content.questions" :key="question.id">
                                        <QuestionViewer v-model="answers[question.id]" :question="question" :index="ind" />
                                    </div>
                                </div>

                                <!-- submit -->
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save</button>
                                </div>
                            </form>

                        </div>

                    </div>

                </template>

            </ContainerWithSideBar>
            <!-- end side navigation bar -->
        </template>

    </BreezeAuthenticatedLayout>
    <!-- end main layout -->

</template>
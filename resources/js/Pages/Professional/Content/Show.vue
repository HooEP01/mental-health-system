<script setup>
// import inertia file
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

// import layout file
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import QuestionViewer from "@/Components/editor/QuestionViewer.vue";

import { computed, ref } from "vue";

// props
const props = defineProps({
    content: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

const answers = ref({});

// destroy function
function destroy(id) {
    Inertia.delete(route('contents.destroy', id));
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
                    Show Your Content
                </template>

                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>

                <template #feature>
                    <!-- edit post -->
                    <li class="flow-root">
                        <Link v-if="can.edit" :href="route('contents.edit', content.id)"
                            class="inline-flex items-center text-left w-full hover:bg-green-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                            <box-icon class='mr-2' name='message-square-edit'></box-icon>
                        <span class="inline-block align-top">Edit This Content</span>
                        </Link>
                    </li>

                    <!-- destroy post -->
                    <li class="flow-root">
                        <Link v-if="can.delete" @click="destroy(content.id)"
                            class="inline-flex items-center text-left w-full hover:bg-red-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                        <box-icon class='mr-2' name='message-square-minus'></box-icon>
                        <span class="inline-block align-top">Delete This Content</span>
                        </Link>
                    </li>

                </template>

                <template #tool>
                    <!-- professional side bar -->
                    <ProfessionalSideBar>
                        <!-- null -->
                    </ProfessionalSideBar>

                </template>

                <template #main>

                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <div class="shadow sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1>{{ content.title }}</h1>
                                <div v-html="content.description" class="prose w-full"></div>
                            </div>
                        </div>

                        <div class="shadow sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <form @submit.prevent="submitSurvey" class="container mx-auto">
                                <p>Question</p>
                                <div v-for="(question, ind) of content.questions" :key="question.id">
                                <QuestionViewer
                                    v-model="answers[question.id]"
                                    :question="question"
                                    :index="ind"
                                />
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

       

                </template>

            </ContainerWithSideBar>
            <!-- end side navigation bar -->
        </template>

    </BreezeAuthenticatedLayout>
    <!-- end main layout -->

</template>
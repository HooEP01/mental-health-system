<script setup>

// import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';

// import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    answers: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

function destroy(id) {
    Inertia.delete(route('answers.destroy', id));
}

</script>
    
<template>

    <Head title="Professional Content" />
    <BreezeAuthenticatedLayout>
        <template #header>
            Professional
        </template>

        <template #content>

            <ContainerWithSideBar>

                <template #title>
                    View Your Content
                </template>

                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>

                <template #feature>
                    <!-- create content -->
                    <Link v-if="can.create" :href="route('answers.create')"
                        class="inline-flex items-center text-left w-full bg-transparent hover:bg-green-100 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                    <box-icon class='mr-2' name='message-square-add'></box-icon>
                    <span class="inline-block align-top">Create New Content</span>
                    </Link>
                </template>

                <template #tool>
                    <!-- professional side bar -->
                    <ProfessionalSideBar>
                        <!-- null -->
                    </ProfessionalSideBar>
                </template>

                <template #main>

                    <div class=" px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <div v-for="content in answers.data" :key="content.id" class="group relative">
                            <div
                                class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                <Link :href="route('answers.show', content.id)">
                                <img v-if="content.image" :src="'/storage/' + content.image" alt="image"
                                    class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                <img v-else
                                    src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                    alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                </Link>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700 line-clamp-1">
                                        <a>
                                            <span aria-hidden="true" class="absolute"></span>
                                            {{ content.title }}
                                        </a>
                                    </h3>
                                    <p v-html="content.description" class="mt-1 text-sm text-gray-500 line-clamp-3"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

            </ContainerWithSideBar>
            <!-- end container with side bar -->
        </template>

    </BreezeAuthenticatedLayout>

</template>
<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        Inertia,
        Link,
        Head,
    },
    props: {
        contents: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
}
</script>
    

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
                    Professional Content
                </template>
                <!--/ #Title -->
                
                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Feature -->
                <template #feature>
                    <!-- create content -->
                    <Link v-if="can.create" :href="route('contents.create')" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                    <box-icon class='mr-2' name='message-square-add'></box-icon>
                    <span class="inline-block align-top">Create New Content</span>
                    </Link>
                </template>
                <!--/ #Feature -->

                <!-- #Tool -->
                <template #tool>
                    <ProfessionalSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <!-- if empty -->
                    <div v-if="(!contents.data.length)" class=" px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <div class="bg-indigo-100 border border-indigo-400 text-indigo-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Content Is Not Available!</strong>
                            <span class="block sm:inline"> Search For Other Keyword Now.</span>
                        </div>
                    </div>
                    <div class="px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <!-- Content Card -->
                        <div v-for="content in contents.data" :key="content.id" class="group relative sm:overflow-hidden sm:rounded-md">
                            <Link :href="route('contents.show', content.id)">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                <img v-if="content.image" :src="'/storage/' + content.image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between p-2 bg-white">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                        <a>
                                            <span aria-hidden="true" class="absolute"></span>
                                            {{ content.title }}
                                        </a>
                                    </h3>
                                    <p v-html="content.description" class="mt-1 text-sm text-gray-600 line-clamp-3"></p>
                                </div>
                            </div>
                            </Link>
                        </div>
                        <!-- Content Card -->
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
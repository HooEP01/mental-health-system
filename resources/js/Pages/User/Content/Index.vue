<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ResourceSideBar from '@/Components/SideBar/ResourceSideBar.vue';
import Pagination from '@/Components/Pagination.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ResourceSideBar,
        Pagination,
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
    <Head title="User Content Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Resource</p>
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
                    Show Content
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <ResourceSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>

                    <div class=" px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <!-- Content Card -->
                        <div v-for="content in contents.data" :key="content.id" class="group relative sm:overflow-hidden sm:rounded-md">
                            <Link :href="route('content.show', content.id)">
                                <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                    <img v-if="content.image" :src="'/storage/' + content.image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                    <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                </div>
                                <div class="mt-4 flex justify-between p-2 bg-white">
                                    <div>
                                        <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                            {{ content.title }}
                                        </h3>
                                        <p v-html="content.description" class="mt-1 text-sm text-gray-600 line-clamp-3"></p>
                                        <p v-if="content.questionCount" class="mt-1 text-sm text-gray-600"> {{ content.questionCount }} Questions
                                            <span v-if="content.format_category === 'Unit'" class="mt-1 text-sm text-gray-600"> * </span>
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        <!-- Content Card -->
                        <pagination class="mt-6 col-span-3" :links="contents.links" />
                    </div>
                </template> 
                <!--/ #Main -->
            </ContainerWithSideBar>
            <!-- end container with side bar -->
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>


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
        answers: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
}
</script>

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
                <p class="text-base font-normal">Professional</p>
                Answer
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
                    Professional Answer
                </template>
                <!--/ #Title -->
                
                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <ProfessionalSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <!-- Content Card -->
                        <div v-for="answer in answers.data" :key="answer.id" class="group relative sm:overflow-hidden sm:rounded-md">
                            <Link :href="route('answers.show', answer.id)">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                <img v-if="answer.image" :src="'/storage/' + answer.image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between p-2 bg-white">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                        {{ answer.title }}
                                    </h3>
                                    <ul class="list-disc pt-2">
                                        <li class="flow-root">
                                            <p class="inline-flex items-center text-left w-full fill-black bg-white text-gray-800  font-base text-sm border border-transparent rounded">
                                                <span class="inline-block align-top text-md font-semibold">{{ answer.updated_at }}</span>
                                            </p>
                                        </li>
                                    </ul>
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
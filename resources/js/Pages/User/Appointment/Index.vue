<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import JourneySideBar from '@/Components/SideBar/JourneySideBar.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        JourneySideBar,
        Inertia,
        Link,
        Head,
    },
    props: {
        appointments: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
    setup() { 
        function showAppointment(appointment) {
            if(appointment.status === "Approve") 
                Inertia.get(route('appointment.show', appointment.id))
        }
        return {showAppointment};
    }
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
                <p class="text-base font-normal">Journey</p>
                Appointment
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
                    View Appointment
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <JourneySideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <!-- if empty -->
                    <div v-if="(!appointments.data.length)" class=" px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <div class="bg-indigo-100 border border-indigo-400 text-indigo-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Appointment Is Not Available!</strong>
                            <span class="block sm:inline"> Search For Other Keyword Now.</span>
                        </div>
                    </div>
                    <div class=" px-4 sm:px-0 md:col-span-3 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-3">
                        <!-- Content Card -->
                        <div v-for="appointment in appointments.data" :key="appointment.id" class="group relative sm:overflow-hidden sm:rounded-md">
                            <Link @click="showAppointment(appointment)">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                <img v-if="appointment.event_image" :src="'/storage/' +appointment.event_image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between p-2 bg-white">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                        <a>
                                            <span aria-hidden="true" class="absolute"></span>
                                            {{ appointment.event_title }}
                                        </a>
                                    </h3>
                                    <p v-html="appointment.event_description" class="mt-1 text-sm text-gray-600 line-clamp-3"></p>
                                    <!-- <p v-if="content.questionCount" class="mt-1 text-sm text-gray-600"> {{ content.questionCount }} Questions
                                        <span v-if="content.format_category === 'Unit'" class="mt-1 text-sm text-gray-600"> * </span>
                                    </p> -->
                                    <p class="mt-1 text-sm text-gray-600">By {{ appointment.professional_title }} {{ appointment.first_name }} {{ appointment.last_name }} | {{ appointment.status }}</p>
                                    <ul class="list-disc pt-2">
                                        <li class="flow-root">
                                            <p class="inline-flex items-center text-left w-full fill-black bg-white text-gray-800  font-base text-sm border border-transparent rounded">
                                                <span class="inline-block align-top text-sm">Start At {{ appointment.start_date }} {{ appointment.start_time }}</span>
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
            <!-- end container with side bar -->
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>

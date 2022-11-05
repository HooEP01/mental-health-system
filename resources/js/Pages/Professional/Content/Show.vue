<script setup>
// import inertia file
import { Head, Link } from '@inertiajs/inertia-vue3';

// import layout file
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SideNavigationBar from '@/Components/SideNavigationBar.vue';
import ProfessionalSideBar from '@/Components/ProfessionalSideBar.vue';

// props
const props = defineProps({
    post: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

// destroy function
function destroy(id) {
    Inertia.delete(route('professional-post.destroy', id));
}
</script>
    
<template>
    <Head title="Post"/>

    <!-- main layout -->
    <BreezeAuthenticatedLayout>

        <!-- header -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Professional
            </h2>
        </template>

        <!-- main content -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                <div class="md:grid md:grid-cols-4 md:gap-3">

                    <!-- side navigation bar -->
                    <SideNavigationBar>

                        <!-- top function bar -->
                        <template #top-bar >

                            <!-- edit post -->
                            <li class="flow-root">
                                <Link :href="route('professional-post.edit', post.id)" 
                                class="inline-flex items-center text-left w-full hover:bg-green-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                                <box-icon class='mr-2' name='message-square-minus'></box-icon>
                                <span class="inline-block align-top">Edit This Post</span>
                                </Link>
                            </li>

                            <!-- destroy post -->
                            <li class="flow-root">
                                <Link  @click="destroy(post.id)"
                                class="inline-flex items-center text-left w-full hover:bg-red-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                                <box-icon class='mr-2' name='message-square-minus'></box-icon>
                                <span class="inline-block align-top">Delete This Post</span>
                                </Link>
                            </li>

                        </template>
                        <!-- end function bar -->

                        <!-- main tools bar -->
                        <template #main-bar>

                            <!-- professional side bar -->
                            <ProfessionalSideBar>
                            </ProfessionalSideBar>

                        </template>

                    </SideNavigationBar>
                    <!-- end side navigation bar -->

                    <!-- card -->
                    <div class="mt-5 md:col-span-3 md:mt-0">
                        <div class="shadow sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h1>{{ post.title }}</h1>
                                <div v-html="post.description" class="prose lg:prose-xl"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->

                </div>
            </div>
        </div>
        <!-- end main content -->

    </BreezeAuthenticatedLayout>
    <!-- end main layout -->

</template>
<script>
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import BreadcrumbHeader from '@/Components/BreadcrumbHeader.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        BreadcrumbHeader,
    },
    props: {
        post: Object,
        errors: Object,
    },
    data() {
        return {
            image_url: null,
        }
    },
    setup(props) {
        const form = useForm({
            id: props.post.id,
            title: props.post.title,
            image: props.post.image,
            slug: null,
            category: props.post.category,
            status: props.post.status,
            description: props.post.description,
        });
       


        function submit() {
            Inertia.put(route('professional-post.update', form.id), form)
        }

        return { form, submit };
    },
    methods: {
        previewImage(e) {
            const file = e.target.files[0];
            this.image_url = URL.createObjectURL(file);
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
        },
    },
}

</script>


<template>

    <Head title="Post" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Professional
            </h2>
        </template>
        <div class="py-12">
            <BreadcrumbHeader title="Create Your Post">
                <div class="flex space-x-2 items-center">
                    <a :href="route('professional-post.index')"
                        class="px-4 py-2 bg-violet-500 uppercase text-white rounded focus:outline-none flex items-center">
                        > Back
                    </a>
                </div>
            </BreadcrumbHeader>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                <div class="md:grid md:grid-cols-3 md:gap-6">

                    <div class="mt-5 md:col-span-3 md:mt-0">

                        <!-- form -->
                        <form @submit.prevent="submit">
                            <div class="shadow sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">

                                    <!-- title -->
                                    <div class="col-span-6">
                                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                        <input v-model="form.title" type="text" name="title" id="title"
                                            autocomplete="title"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- profile image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cover photo</label>
                                        <div
                                            class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                            <div class="space-y-1 text-center">
                                                <img v-if="form.image" :src="'/storage/' + form.image" class="w-64 h-48 object-cover"/>
                                                <img v-else="image_url" :src="image_url" :alt="form.title"
                                                    class="w-64 h-48 object-cover" />
                                                <svg v-else class="mx-auto h-12 w-12 text-gray-400"
                                                    stroke="currentColor" fill="none" viewBox="0 0 48 48"
                                                    aria-hidden="true">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                                        <span>Upload a Photo</span>
                                                        <input @change="previewImage" ref="photo" type="file"
                                                            id="file-upload" name="file-upload" class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- category & status -->
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="type"
                                                class="block text-sm font-medium text-gray-700">Category</label>
                                            <select id="category" name="category" autocomplete="category-name"
                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                                v-model="form.category">
                                                <option value="1" selected>Article</option>
                                                <option value="2">Case Study</option>
                                                <option value="3">Survey</option>
                                                <option value="4">Journal</option>
                                                <option value="5">Report</option>
                                            </select>
                                        </div>
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="status"
                                                class="block text-sm font-medium text-gray-700">Status</label>
                                            <select id="status" name="status" autocomplete="status-name"
                                                class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                                v-model="form.status">
                                                <option value="1" selected>Draft</option>
                                                <option value="2">Complete</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <div>
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700">Description</label>
                                        <div class="mt-1">
                                            <textarea v-model="form.description" id="description" name="description"
                                                rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="description"></textarea>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Description for your post. URLs are
                                            hyperlinked.</p>
                                    </div>
                                </div>

                                <!-- submit -->
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
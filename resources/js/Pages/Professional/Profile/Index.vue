<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import SettingSideBar from '@/Components/SideBar/SettingSideBar.vue';
// Import inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        SettingSideBar,
        Inertia,
        useForm,
        Head,
    },
    props: {
        user: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
    data() {
        return {
            image_url: null,
            file_name: null,
        }
    },
    setup(props) {
        // Set NULL
        if(props.user.professional_title == null){
            props.user.professional_title = '';
            props.user.professional_description = '';
            props.user.professional_status = 'Register';
            props.user.image = '';
            props.user.file = '';
        }
        // Form
        const form = useForm({
            id: props.user.id,
            image: props.user.image,
            file: props.user.file,
            professional_title: props.user.professional_title,
            professional_description: props.user.professional_description,
            professional_status: props.user.professional_status,
        });
        // Profiles Store
        function submit() {
            Inertia.post(route('profiles.store'), form)
        }
        return { form, submit };
    },
    methods: {
        // Preview Image
        previewImage(e) {
            const file = e.target.files[0];
            this.image_url = URL.createObjectURL(file);
            if (this.$refs.photo) {
                this.form.image = this.$refs.photo.files[0];
            }
        },

        // Preview File
        previewFile(e) {
            const file = e.target.files[0].name;
            this.file_name = file;
            if (this.$refs.file) {
                this.form.file = this.$refs.file.files[0];
            }
        },
    },
}
</script>


<template>
    <!-- Header -->
    <Head title="Professional Profile Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Profile</p>
                Professional
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
                    Professional Profile
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <SettingSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- Form -->
                        <form @submit.prevent="submit">

                            <div class="sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <!-- Profile -->
                                    <h1 class="text-xl font-bold text-indigo-500">Professional Profile</h1>
                                    <div class="grid grid-cols-6 gap-6">
                                        <!-- Professional Title -->
                                        <div class="col-span-6">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-slate-600">Professional Title</label>
                                            <input v-model="form.professional_title" type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Professional Title -->

                                        <!-- Professional Description -->
                                        <div class="col-span-6">
                                            <label for="about" class="block text-sm font-medium text-gray-700">Professional Description</label>
                                            <div class="mt-1">
                                                <textarea v-model="form.professional_description" id="professional_description" name="professional_description" rows="3" class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="you@example.com"></textarea>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs are hyperlinked.</p>
                                        </div>
                                        <!--/ Professional Description -->

                                        <!-- Image -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-slate-600">Photo</label>
                                            <div class="mt-1 flex items-center">
                                                <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                                <!-- if exist image url -->
                                                <img v-if="image_url" :src="image_url" :alt="form.title" class="w-64 h-48 object-cover" />
                                                <img v-else-if="form.image" :src="'/storage/' + form.image" class="w-64 h-48 object-cover" />
                                                <svg v-else class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                </span>
                                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-slate-100 hover:bg-slate-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded ml-2">
                                                <span>Upload a Image</span>
                                                <input @change="previewImage" ref="photo" type="file" id="file-upload" name="file-upload" class="sr-only">
                                            </label>
                                            </div>
                                        </div>
                                        <!--/ Image -->

                                        <!-- Professional Status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="professional_status" class="block text-sm font-medium text-slate-600">Professional Status</label>
                                            <input v-model="form.professional_status" readonly type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!--/ Professional Status -->

                                        <!-- Image -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <label class="block text-sm font-medium text-slate-600">Resume</label>
                                            <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-400 px-6 pt-5 pb-6">
                                                <div class="space-y-1 text-center">
                                                    
                                                    <div class="flex text-sm text-gray-600">
                                                        <span v-if="file_name" :src="file_name" class="pr-1"> {{ file_name }} |</span>
                                                        <a v-else-if="form.file" :href="('/storage/' + form.file)" class="pr-1"> Download </a>
                                                        <label for="file-upload-2" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                                            <span>Upload a File</span>
                                                            <input @change="previewFile" ref="file" type="file" id="file-upload-2" name="file-upload-2" class="sr-only">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PDF, WORD up to 10MB</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ Image -->

                                    </div>
                                </div>
                             <!-- Submit -->
                                <div class="bg-white px-4 py-3 text-right sm:px-6">
                                       <button type="submit" class="inline-flex justify-center fill-white rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <box-icon class='mr-2' name='cube'></box-icon> 
                                        <span class="inline-block align-top text-base mr-2">Save The Profile</span>
                                    </button>
                                </div>
                                <!--/ Submit -->
                            </div>
                        </form>
                        <!--/ Form -->
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

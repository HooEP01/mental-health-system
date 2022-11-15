<script>

// import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import SettingSideBar from '@/Components/SideBar/SettingSideBar.vue';

// import inertia
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

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
        errors: Object,
        user: String,
    },
    data() {
        return {
            image_url: null,
        }
    },

    setup(props) {
        const form = useForm({
            id: props.user.id,
            image: props.user.image,
            professional_title: props.user.professional_title,
            professional_description: props.user.professional_description,
            professional_status: props.user.professional_status,
        });

        function submit() {
            Inertia.post(route('profile.store'), form)
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
    <Head title="Profile" />
    <BreezeAuthenticatedLayout>
        <template #header>
            Profile
        </template>
        <template #content>
            <ContainerWithSideBar>
                <template #title>
                    Join Us
                </template>
                <template #subtitle>
                    We are welcoming all the mental health professional to join us.
                </template>
                <template #tool>
                    <!-- professional side bar -->
                    <SettingSideBar></SettingSideBar>
                </template>
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <!-- form submit -->
                        <form @submit.prevent="submit">
                            <div class="shadow sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <!-- professional title -->
                                        <div class="col-span-6">
                                            <label for="first-name"
                                                class="block text-sm font-medium text-gray-700">Professional Title</label>
                                            <input v-model="form.professional_title" type="text" name="professional_title" id="professional_title" autocomplete="given-professional_title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <!-- professional description -->
                                        <div class="col-span-6">
                                            <label for="about" class="block text-sm font-medium text-gray-700">Professional Description</label>
                                            <div class="mt-1">
                                                <textarea v-model="form.professional_description" id="professional_description" name="professional_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="you@example.com"></textarea>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs are hyperlinked.</p>
                                        </div>
                                        <!-- image -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Photo</label>
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
                                        <!-- professional status -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="professional_status" class="block text-sm font-medium text-gray-700">Professional Status</label>
                                            <input v-model="form.professional_status" type="text" name="professional_status" id="professional_status" autocomplete="professional_status" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                                <!-- submit -->
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save</button>
                                </div>
                            </div>
                        </form>
                        <!-- end form -->
                    </div>
                </template>
            </ContainerWithSideBar>
        </template>
    </BreezeAuthenticatedLayout>
</template>

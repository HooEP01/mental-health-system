<script setup>
// Import
import { v4 as uuidv4 } from "uuid";
import { ref } from "@vue/reactivity";
import { Link } from '@inertiajs/inertia-vue3';
import { isBooleanAttr } from "@vue/shared";
// Prop
const { contents, event, index, appointment, modelValue } = defineProps({
    appointment: Object,
    contents: Object,
    event: Object,
    index: Number,
    modelValue: [String, Array, Object],
});

const emits = defineEmits(["update:modelValue"]);

function selectContent(content){
    emits("update:modelValue", content);
}

</script>



<template>
    <div class="mt-4 border border-gray-400 sm:overflow-hidden sm:rounded-md">
        <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                <!-- Table -->
                <table
                    class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                    <thead class="text-xs text-gray-700 uppercase">
                        <tr class="bg-white border-b border-gray-400">
                            <th scope="col" class="py-3 px-6">#</th>
                            <th scope="col" class="py-3 px-6">Title</th>
                            <th scope="col" class="py-3 px-6">Description</th>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">View</th>
                            <th scope="col" class="py-3 px-6">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(content, index) in contents" :key="content.id"
                            class="bg-white border-b border-gray-400">
                            <td data-label="Title" class="py-4 px-6">
                                {{ index + 1 }}
                            </td>
                            <td data-label="Title" class="py-4 px-6">
                                {{ content.title }}
                            </td>
                            <td data-label="Description" class="py-4 px-6">
                                <p v-html="content.description" class="mt-1 text-sm text-gray-500 line-clamp-3"></p>
                            </td>
                            <td data-label="User_id" class="py-4 px-6">
                                {{ content.name }}
                            </td>
                            <td data-label="View" class="py-4 px-6">
                                <Link v-if="appointment" :href="route('appointments.contents.show', [appointment.id, content.id])" class="inline-flex items-center text-left fill-white text-white w-full bg-yellow-400 hover:bg-yellow-500 font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class='mr-1' name='show-alt'></box-icon>
                                    <span class="mr-1 inline-block align-top">View</span>
                                </Link> 

                                <Link v-else :href="route('events.contents.show', [event.id, content.id])" class="inline-flex items-center text-left fill-white text-white w-full bg-yellow-400 hover:bg-yellow-500 font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class='mr-1' name='show-alt'></box-icon>
                                    <span class="mr-1 inline-block align-top">View</span>
                                </Link> 
                            </td>
                            <td data-label="Action" class="py-4 px-6">
                                <Button type="Button" @click="selectContent(content)" class="inline-flex items-center text-left fill-white text-white w-full bg-indigo-500 hover:bg-indigo-600 font-semibold py-3 px-4 border border-transparent rounded">
                                <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                <span class="inline-block align-top">Select</span>
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--/ Table -->
            </div>
        </div>
    </div>
</template>
<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { useForm } from '@inertiajs/inertia-vue3';
    import { computed } from 'vue';
    import Dropdown from '@/Components/Dropdown.vue';

    const props = defineProps({
        chats: Object, default: () => ({}),
        appointment: Object, default: () => (0),
    })

    const model = useForm({
        appointment_id: props.appointment.id,
        chat_id: null,
        message: null,
    });

    const chats = computed(() => props.chats)

    const destroyChat = (chatID) => {
        Inertia.delete(route('appointment.chat.destroy', [props.appointment.id, chatID]))
    }

    const editChat = (chat) => {
        model.chat_id = chat.id
        model.message = chat.message
    }

    const resetChat = () => {
        model.reset();
    };

    const submitChat = () => {
        Inertia.post(route('appointment.chat.store', model.appointment_id), model);
        model.reset();
    }

</script>

<template>
    <form @submit.prevent="submitChat">
        <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
            <h1 class="text-3xl font-bold">Chat</h1>
            <div class="bg-white border border-slate-400 sm:overflow-hidden sm:rounded-md">
                <div class="flex justify-between">
                    <div class="px-6 py-6 font-bold">
                        Message
                    </div>
                </div>
                <div class="flex flex-col px-6 pb-6">
                    <div v-for="chat in chats" :key="chat.id">
                        <div v-if="chat.user_id != appointment.user_id">
                            <div v-if="chat.status != 'Deleted'">
                                <p class="text-base font-medium text-slate-600 mt-3 text-right">{{ chat.message }}</p>
                                <p class="text-sm font-base text-slate-400 text-right">{{ chat.updated_at }} {{ chat.status }} </p>
                                <p class="text-sm font-base text-slate-400 text-right">By {{ chat.name }} </p>
                            </div>
                            <div v-else>
                                <p class="text-base font-medium text-slate-400 mt-3 text-right">This message has been {{ chat.status.toLowerCase() }}</p>
                                <p class="text-sm font-base text-slate-400 text-right">By {{ chat.name }} </p>
                            </div>
                        </div>
                        <div v-else>
                            <div v-if="chat.status != 'Deleted'" class="flex justify-between">
                                <div>
                                    <p class="text-base font-medium text-slate-600 mt-3">{{ chat.message }}</p>
                                    <p class="text-sm font-base text-slate-400">{{ chat.updated_at }} {{ chat.status }}</p>
                                </div>
                                <Dropdown class="flex justify-end">
                                    <template #trigger>
                                        <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                                            <span class="sr-only">Open dropdown</span>
                                            <box-icon name='dots-horizontal-rounded'></box-icon>
                                        </button>
                                    </template>
                                    <template #content>
                                        <ul class="py-1" aria-labelledby="dropdownButton">
                                            <li>
                                                <a @click="editChat(chat)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                            </li>
                                            <li>
                                                <a @click="destroyChat(chat.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                            </li>
                                        </ul>
                                    </template>
                                </Dropdown>
                            </div>
                            <div v-else>
                                <p class="text-base font-medium text-slate-400 mt-3">You {{ chat.status.toLowerCase() }} this message</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Message input -->
                <div class="flex px-6 pb-6">
                    <label for="title" class="pr-4 pt-2 flex-none w-16 text-sm font-medium text-slate-600">Message</label>
                    <input v-model="model.message" type="text" name="title" id="title" autocomplete="title" required class="flex-initial block w-full rounded-md border-gray-400 mr-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <button v-if="model.chat_id" @click="resetChat()" type="button" class="flex-none pt-1 w-14 mr-2 justify-center rounded-md border border-transparent fill-white bg-red-500 text-sm font-medium text-white shadow-sm hover:bg-red-600">
                        <box-icon name='message-square-x'></box-icon>
                    </button>
                    <button type="submit" class="flex-none pt-1 w-14 justify-center rounded-md border border-transparent fill-white bg-indigo-500 text-sm font-medium text-white shadow-sm hover:bg-indigo-600">
                        <box-icon name='send'></box-icon>
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
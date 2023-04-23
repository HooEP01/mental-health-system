<script setup>
    import { Inertia } from "@inertiajs/inertia"
    import { ref } from "@vue/reactivity"

    const props = defineProps({
        tasks: Object, default: () => ({}),
        appointmentID: [Number, String], default: () => (0)
    });
    
    const tasks = ref(JSON.parse(JSON.stringify(props.tasks)))

    const showTask = (taskID) => {
        Inertia.get(route('appointment.task.show', [props.appointmentID, taskID]))
    }

    const showAnswer = (answerID) => {
        Inertia.get(route('appointment.answer.show', [props.appointmentID, answerID]))
    }
</script>

<template>
    <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
        <h1 class="text-3xl font-bold">Task</h1>
        <div v-for="(task, index) of tasks" :key="task.id">
            <div class="bg-violet-50 sm:overflow-hidden sm:rounded-md">
                <div class="flex justify-between">
                    <div class="px-6 py-6 font-bold">
                        {{ index + 1 }}. {{ task.title }}
                    </div>
                </div>
                <div class="flex flex-col items-left px-6 pb-6">
                    <p v-html="task.description" class="text-base font-medium text-slate-600"></p>
                    <div class="flex mt-2 space-x-3 md:mt-2">
                        <Link @click="showTask(task.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-violet-500 hover:fill-white text-white bg-violet-400 font-semibold py-3 px-4 border border-transparent rounded">
                            <box-icon class="mr-2" name='spreadsheet'></box-icon>
                            <span class="inline-block align-top">{{ task.content_title }}</span>
                        </Link>
                    </div>
                    <div v-if="task.answer.length" class="grid grid-cols-12 mt-2 gap-2">
                        <div v-for="(answer, index) of task.answer" class="col-span-3 sm:col-span-2">
                            <Link @click="showAnswer(answer.id)" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-violet-600 hover:fill-white text-white bg-violet-500 font-semibold py-3 px-4 border border-transparent rounded">
                                <box-icon class="mr-2" type='solid' name='card'></box-icon>
                                <span class="inline-block align-top">Ans: {{ index + 1 }}</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
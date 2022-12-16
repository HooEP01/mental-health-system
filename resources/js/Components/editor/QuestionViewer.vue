<script setup>
import { ref, toRef } from "vue";
import Dropdown from '@/Components/Dropdown.vue';

const { question, index, modelValue } = defineProps({
  question: Object,
  index: Number,
  modelValue: [String, Array, Object],
});


const emits = defineEmits(["update:modelValue"]);

let model;
if (question.category === "checkbox") {
  model = ref({});

  if(Array.isArray(modelValue)) {
    for(let i = 0; i < modelValue.length; i++) {
      for(let option of question.data.options) {
        if(option.uuid === modelValue[i].uuid) {
          model.value[option.uuid] = true;
        }
      }
    }
  }
}


function onCheckboxChange() {
  const selectedOptions = [];
  for (let theUUID in model.value) {
    
    if ( model.value[theUUID] ) {
      let value = question.data.options.find(({ uuid }) => uuid === theUUID);
      selectedOptions.push({uuid: value.uuid, text: value.text, format: value.format});
    }
  }
  emits("update:modelValue", selectedOptions);
}


function onOptionChange(value) {
  const selectedOption = {uuid: value.uuid, text: value.text, format: value.format};
  emits("update:modelValue", selectedOption);
}

function upperCaseFirst(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>



<template>

  <div class="bg-emerald-50 sm:overflow-hidden sm:rounded-md">

    <div class="flex justify-between">
      <div class="px-6 py-6 font-bold">
        {{ index + 1 }}. {{ question.question }}
      </div>
    <div></div>
    <Dropdown class="place-content-end px-6 py-4">
      <template #trigger>
        <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
            <span class="sr-only">Open dropdown</span>
                <box-icon name='dots-horizontal-rounded'></box-icon>
        </button>
      </template>

      <template #content>
        <ul class="py-1" aria-labelledby="dropdownButton">
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">{{ upperCaseFirst(question.category) }}</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
            </li>
         </ul>
         </template>
    </Dropdown>
    </div>

    <div class="flex flex-col items-left px-6 pb-6">
    <fieldset>
      <div>
        <p class="text-base font-medium text-slate-600">
          {{ question.description }}
        </p>
      </div>
      <div class="mt-3">
        <!-- select -->
        <div v-if="question.category === 'select'">
          <select v-model="modelValue" @change="onOptionChange(modelValue)" class="mt-1 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
            <option>Please Select</option>
            <option v-for="option in question.data.options" :key="option.uuid"  :value="option">
              {{ option.text }}
            </option>
          </select>
        </div>
        <!-- radio -->
        <div v-else-if="question.category === 'radio'">
          <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center">
            <input v-model="modelValue" :value="option" :id="option.uuid" :name="'question' + question.id"  @change="onOptionChange(option)" type="radio" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-400" />
            <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">
              {{ option.text }}
            </label>
          </div>
        </div>
        <!-- checkbox -->
        <div v-else-if="question.category === 'checkbox'">
          <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center">
            <input :id="option.uuid" v-model="model[option.uuid]" :value="option.text" @change="onCheckboxChange()" type="checkbox" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-400 rounded"/>
            <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">
              {{ option.text }}
            </label>
          </div>
        </div>
        <!-- text -->
        <div v-else-if="question.category === 'text'">
          <input type="text" :value="modelValue" @input="emits('update:modelValue', $event.target.value)" class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md"/>
        </div>
        <!-- textarea -->
        <div v-else-if="question.category === 'textarea'">
          <textarea :value="modelValue" @input="emits('update:modelValue', $event.target.value)" class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md"></textarea>
        </div>
      </div>
    </fieldset>
  </div>
  </div>

  </template>
  
 
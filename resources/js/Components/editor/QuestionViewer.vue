<template>
    <fieldset class="mb-4">
      <div>
        <legend class="text-base font-medium text-gray-900">
          {{ index + 1 }}. {{ question.question }}
        </legend>
        <p class="text-gray-500 text-sm">
          {{ question.description }}
        </p>
      </div>
      <div class="mt-3">
        <!-- select -->
        <div v-if="question.category === 'select'">
          <select :value="modelValue" @change="emits('update:modelValue', $event.target.value)" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">Please Select</option>
            <option v-for="option in question.data.options" :key="option.uuid" :value="option.text">
              {{ option.text }}
            </option>
          </select>
        </div>
        <!-- radio -->
        <div v-else-if="question.category === 'radio'">
          <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center">
            <input v-model="modelValue" :id="option.uuid" :name="'question' + question.id" :value="option.text" @change="emits('update:modelValue', $event.target.value)" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" />
            <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">
              {{ option.text }}
            </label>
          </div>
        </div>
        <!-- checkbox -->
        <div v-else-if="question.category === 'checkbox'">
          <div v-for="(option, ind) of question.data.options" :key="option.uuid" class="flex items-center">
            <input :id="option.uuid" v-model="model[option.text]" @change="onCheckboxChange" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
            <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">
              {{ option.text }}
            </label>
          </div>
        </div>
        <!-- text -->
        <div v-else-if="question.category === 'text'">
          <input type="text" :value="modelValue" @input="emits('update:modelValue', $event.target.value)" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
        </div>
        <!-- textarea -->
        <div v-else-if="question.category === 'textarea'">
          <textarea :value="modelValue" @input="emits('update:modelValue', $event.target.value)" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
        </div>
      </div>
    </fieldset>
    <!-- hr -->
    <br />
  </template>
  
  <script setup>
  import { ref } from "vue";
  const { question, index, modelValue } = defineProps({
    question: Object,
    index: Number,
    modelValue: [String, Array],
  });
  const emits = defineEmits(["update:modelValue"]);
  
  let model;
  if (question.category === "checkbox") {
    model = ref({});
  
  }
  
  function shouldHaveOptions() {
    return ["select", "radio", "checkbox"].includes(question.category);
  }
  
  function onCheckboxChange($event) {
    const selectedOptions = [];
    for (let uuid in model.value) {
      if (model.value[uuid]) {
        selectedOptions.push(uuid);
      }
    }
    emits("update:modelValue", selectedOptions);
  }
  </script>
  

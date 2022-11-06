<template>
  <!-- Question index -->
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">
      {{ index + 1 }}. {{ model.question }}
    </h3>


    <div class="flex items-center">
      <!-- Add new question -->
      <button type="button" @click="addQuestion()" class="flex items-center rounded-md text-xs py-1 px-3 mr-2 text-white bg-green-400 hover:bg-green-500">
        Add New Question Below
      </button>
      <!--/ Add new question -->

      <!-- Delete question -->
      <button type="button" @click="deleteQuestion()" class="flex items-center rounded-md text-xs py-1 px-3 mr-2 text-white bg-red-400 hover:bg-red-500">
        Delete this Question
      </button>
      <!--/ Delete question -->
    </div>
  </div>
  <!--/ Question index -->
  <div class="grid gap-3 grid-cols-12">
    <!-- Question -->
    <div class="mt-3 col-span-9">
      <label :for="'question_text_' + model.data" class="block text-sm font-medium text-gray-700">Question Text</label>
      <input type="text" :name="'question_text_' + model.data" v-model="model.question" @change="dataChange"
        :id="'question_text_' + model.data" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
          " />
    </div>
    <!--/ Question -->

    <!-- Question Category -->
    <div class="mt-3 col-span-3">
      <label for="question_category" class="block text-sm font-medium text-gray-700">Select Question Type</label>
      <select id="question_category" name="question_category" v-model="model.category" @change="categoryChange" class="
            mt-1
            block
            w-full
            py-2
            px-3
            border border-gray-300
            bg-white
            rounded-md
            shadow-sm
            focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
            sm:text-sm
          ">
        <option v-for="category in questionCategory" :key="category" :value="category">
          {{ upperCaseFirst(category) }}
        </option>
      </select>
    </div>
    <!--/ Question Category -->
  </div>

  <!-- Question Description -->
  <div class="mt-3 col-span-9">
    <label :for="'question_description_' + model.id" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea :name="'question_description_' + model.id" v-model="model.description" @change="dataChange"
      :id="'question_description_' + model.id" class="
          mt-1
          focus:ring-indigo-500 focus:border-indigo-500
          block
          w-full
          shadow-sm
          sm:text-sm
          border-gray-300
          rounded-md
        " />
  </div>
  <!--/ Question Description -->

  <!-- Data -->
  <div>
    <div v-if="shouldHaveOptions()" class="mt-2">
      <h4 class="text-sm font-semibold mb-1 flex justify-between items-center">
        Options

        <!-- Add new option -->
        <button type="button" @click="addOption()" class="
              flex
              items-center
              text-xs
              py-1
              px-2
              rounded-sm
              text-white
              bg-gray-600
              hover:bg-gray-700
            ">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
              clip-rule="evenodd" />
          </svg>
          Add Option
        </button>
        <!--/ Add new option -->
      </h4>

      <!-- Option list -->
      <div v-for="(option, index) in model.data.options" :key="option.uuid" class="flex items-center mb-1">
        <span class="w-6 text-sm"> {{ index + 1 }}. </span>
        <input type="text" tabindex="1" v-model="option.text" @change="dataChange" class="w-full rounded-sm py-1 px-2 text-xs border border-gray-300 focus:border-indigo-500
            " />
        <!-- Delete Option -->
        <button type="button" @click="removeOption(option)" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100
            ">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-red-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
              clip-rule="evenodd" />
          </svg>
        </button>
        <!--/ Delete Option -->
      </div>
      <!--/ Option list -->
    </div>
  </div>
  <!--/ Data -->

  <hr class="my-4" />
</template>
  
<script setup>
import { v4 as uuidv4 } from "uuid";
import { ref } from "@vue/reactivity";

const props = defineProps({
  question: Object,
  index: Number,
});

const emit = defineEmits(["change", "addQuestion", "deleteQuestion"]);

// Re-create the whole question data to avoid unintentional reference change
const model = ref(JSON.parse(JSON.stringify(props.question)));

// Get question category
const questionCategory = ["text", "select", "radio", "checkbox", "textarea"];

function upperCaseFirst(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function getOptions() {
  return model.value.data.options;
}

function setOptions(options) {
  model.value.data.options = options;
}

// Check if the question should have options
function shouldHaveOptions() {
  return ["select", "radio", "checkbox"].includes(model.value.category);
}

// Add option
function addOption() {
  setOptions([
    ...getOptions(),
    { uuid: uuidv4(), text: "" },
  ]);
  dataChange();
}

// Remove option
function removeOption(op) {
  setOptions(getOptions().filter((opt) => opt !== op));
  dataChange();
}

function categoryChange() {
  if (shouldHaveOptions()) {
    setOptions(getOptions() || []);
  }
  dataChange();
}

// Emit the data change
function dataChange() {
  const data = model.value;
  if (!shouldHaveOptions()) {
    delete data.data.options;
  }

  emit("change", data);
}

function addQuestion() {
  emit("addQuestion", props.index + 1);
}

function deleteQuestion() {
  emit("deleteQuestion", props.question);
}
</script>
  
<style>

</style>
  
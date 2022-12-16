<script setup>
// Import
import { v4 as uuidv4 } from "uuid";
import { ref } from "@vue/reactivity";

const props = defineProps({
  question: Object,
  formats: Object,
  index: Number,
});

const emit = defineEmits(["change", "addQuestion", "deleteQuestion"]);
const point = ref();

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
    { uuid: uuidv4(), text: "", format: []},
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

function formatChange(optionID, format, event) {

    setFormats( optionID, [
        ...getFormats(optionID) || [],
        { id: format.id, unit: format.unit, point: event },
      ]
    );

  dataChange();
}

function getFormats(id) {
  return model.value.data.options.find(({ uuid }) => uuid === id).format;
}

function setFormats(id, formats) {
  return model.value.data.options.find(({ uuid }) => uuid === id).format = formats;
}

function existOptionFormat(option, format) {
  let currentFormat;
  if(option.format){
   currentFormat = option.format.find(({ id }) => id === format.id);
  }else{
    currentFormat = false;
  }
  

  if( currentFormat ) {
    return true;
  } else {
    return false;
  }
}
</script>
  

<template>
  <!-- Question index -->
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">
      {{ index + 1 }}. {{ model.question }}
    </h3>

    <div class="flex items-center">
      <!-- Add new question -->
      <button type="button" @click="addQuestion()" class="flex items-center rounded-md text-xs py-1 px-3 mr-2 fill-white text-white bg-indigo-500 hover:bg-indigo-600">
        <box-icon class="mr-2" name='message-square-add'></box-icon>
        <span class="inline-block align-top text-base mr-2">Create Question</span>
      </button>
      <!--/ Add new question -->

      <!-- Delete question -->
      <button type="button" @click="deleteQuestion()" class="flex items-center rounded-md text-xs py-1 px-3 fill-white text-white bg-red-400 hover:bg-red-500">
        <box-icon class="mr-2" name='message-square-minus'></box-icon>
        <span class="inline-block align-top text-base mr-2">Delete Question</span>
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
        :id="'question_text_' + model.data" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md " />
    </div>
    <!--/ Question -->

    <!-- Question Category -->
    <div class="mt-3 col-span-3">
      <label for="question_category" class="block text-sm font-medium text-gray-700">Select Question Type</label>
      <select id="question_category" name="question_category" v-model="model.category" @change="categoryChange" class="
            mt-1 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
    <textarea :name="'question_description_' + model.id" v-model="model.description" @change="dataChange" :id="'question_description_' + model.id" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md" />
  </div>
  <!--/ Question Description -->



  <!-- Data -->
  <div>
    <div v-if="shouldHaveOptions()" class="mt-2">
      <h4 class="text-sm font-base text-gray-700 mb-2 flex justify-between items-center">
        Options

        <!-- Add new option -->
        <button type="button" @click="addOption()" class=" flex items-center text-xs py-1 px-2 rounded-md text-white fill-white bg-indigo-500 hover:bg-indigo-600">
            <box-icon class="mr-2" name='message-square-add'></box-icon>
            <span class="inline-block align-top text-base mr-2">Create Option</span>
        </button>
        <!--/ Add new option -->
      </h4>

      <!-- Option list -->
      <div v-for="(option, index) in model.data.options" :key="option.uuid" class="grid grid-cols-12 gap-4">

        <div class="col-span-12 mt-4">
          <div class="flex items-center">
            <p class="w-6 text-sm"> {{ index + 1 }}. </p>
            <textarea type="text" tabindex="1" v-model="option.text" @change="dataChange" class="h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md"/>
          </div>
        </div>

        <!-- Format -->
        <div v-for="format in props.formats" :key="format.id" class="col-span-2">
          <div v-if="existOptionFormat(option, format)">
            <div class="flex items-center">
              <input type="number" v-model="option.format.find(({ id }) => id === format.id).point" @change="formatChange(option.uuid, format)" :name="'format_' + format.id" :id="'format_' + format.id" class="h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md" />
              <label :for="('format_' + format.id)" class="text-sm ml-2">{{ upperCaseFirst(format.unit) }}</label>
            </div>
          </div>

          <div v-else>
            <div class="flex items-center">
              <input type="number" @change="formatChange(option.uuid, format, $event.target.value)" :name="'format_' + format.id" :id="'format_' + format.id" class="h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md" />
              <label :for="('format_' + format.id)" class="text-sm ml-2">{{ upperCaseFirst(format.unit) }}</label>
            </div>
          </div>

        </div>
        <!--/ Format -->
        
        <!-- Delete Option -->
        <div class="col-span-1">
          <button type="button" @click="removeOption(option)" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100">
              <box-icon class="fill-red-500" name='trash'></box-icon>
          </button>
        </div>
        <!--/ Delete Option -->
      </div>
      <!--/ Option list -->
    </div>
  </div>
  <!--/ Data -->

</template>

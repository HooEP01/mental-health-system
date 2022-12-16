<script setup>
// Import
import { v4 as uuidv4 } from "uuid";
import { ref } from "@vue/reactivity";

// Prop
const props = defineProps({
  format: Object,
  index: Number,
});

// Emit
const emit = defineEmits(["change", "addFormat", "deleteFormat"]);

// Re-create the whole format data to avoid unintentional reference change
const model = ref(JSON.parse(JSON.stringify(props.format)));

// Options
function getOptions() {
  return model.value.data.options;
}

function setOptions(options) {
  model.value.data.options = options;
}

function addOption() {

  let minValue = null;
  if(Object.keys(model.value.data).length != 0) {
    let lastArray = getOptions().slice(-1);
    if(lastArray.length){
      if(lastArray[0].max != ""){
        minValue = lastArray[0].max + 1;
      }
    }
    
  }
  

  setOptions([
    ...getOptions() || [],
    { uuid: uuidv4(), min: minValue, max: "", text: "" },
  ]);
  dataChange();
}

function removeOption(op) {
  setOptions(getOptions().filter((opt) => opt !== op));
  dataChange();
}

// Emit the data change
function dataChange() {
  const data = model.value;
  emit("change", data);
}

function addFormat() {
  emit("addFormat", props.index + 1);
}

function deleteFormat() {
  emit("deleteFormat", props.format);
}
</script>
  

<template>
  <!-- Unit index -->
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">
      {{ index + 1 }}. {{ model.unit }}
    </h3>

    <div class="flex items-center">
      <!-- Add new unit -->
      <button type="button" @click="addFormat()" class="flex items-center rounded-md text-xs py-1 px-3 mr-2 fill-white text-white bg-indigo-500 hover:bg-indigo-600">
        <box-icon class="mr-2" name='message-square-add'></box-icon>
        <span class="inline-block align-top text-base mr-2">Create Format</span>
      </button>
      <!--/ Add new unit -->

      <!-- Delete unit -->
      <button type="button" @click="deleteFormat()" class="flex items-center rounded-md text-xs py-1 px-3 fill-white text-white bg-red-400 hover:bg-red-500">
        <box-icon class="mr-2" name='message-square-minus'></box-icon>
        <span class="inline-block align-top text-base mr-2">Delete Format</span>
      </button>
      <!--/ Delete unit -->
    </div>
  </div>
  <!--/ Unit index -->

  <div class="grid gap-3 grid-cols-12">
    <!-- Unit -->
    <div class="mt-3 col-span-3">
      <label :for="'unit_' + model.id" class="block text-sm font-medium text-gray-700">Unit</label>
      <input type="text" v-model="model.unit" @change="dataChange" :name="'unit_' + model.id" :id="'unit_' + model.id" placeholder="unit" class="h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md " />
    </div>
    <!--/ Unit -->

    <!-- Unit Description -->
    <div class="mt-3 col-span-9">
      <label :for="'description_' + model.id" class="block text-sm font-medium text-gray-700">Unit Description</label>
      <textarea v-model="model.description" @change="dataChange" :name="'description_' + model.id" :id="'description_' + model.id" placeholder="description" class="h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md" />
    </div>
    <!--/ Unit Description -->
  </div>


  <!-- Data -->
  <div>
    <div class="mt-2">
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
      <div v-for="(option, index) in model.data.options" :key="option.uuid" class="flex items-center mb-1">
        <span class="w-6 text-sm"> {{ index + 1 }}. </span>

        <input type="number" v-model="option.min" @change="dataChange" tabindex="1" placeholder="min" class="mr-2 h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-20 shadow-sm sm:text-sm border-gray-400 rounded-md"/>

        <input type="number" v-model="option.max" @change="dataChange" tabindex="1" placeholder="max" class="mr-2 h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-20 shadow-sm sm:text-sm border-gray-400 rounded-md"/>

        <textarea  tabindex="1" v-model="option.text" @change="dataChange" placeholder="description" class="mr-2 h-10 mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-400 rounded-md"/>
        
        <!-- Delete Option -->
        <button type="button" @click="removeOption(option)" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100">
            <box-icon class="fill-red-500" name='trash'></box-icon>
        </button>
        <!--/ Delete Option -->
      </div>
      <!--/ Option list -->
    </div>
  </div>
  <!--/ Data -->

</template>

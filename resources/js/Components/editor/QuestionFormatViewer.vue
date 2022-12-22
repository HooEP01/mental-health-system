<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import { ref } from "vue";
const { answers, content, index } = defineProps({
  answers: Object,
  content: Object,
});

let points = [];

const sorter = {
  "select": 1,
  "radio": 1,
  "checkbox": 2,
  "text": 3,
  "textarea": 3,
}

for(let answer of answers) {
  let category = sorter[answer.content_question_category];

  if( category === 1 ) {

    for(let i = 0; i < answer.answer.format.length; i++) {
      points.push(answer.answer.format[i]);
    }
    
  } else if( category === 2 ) {

    for(let i = 0; i < answer.answer.length; i++) {
      for(let j = 0; j < answer.answer[i].format.length; j++) {
        points.push(answer.answer[i].format[j]);
      }
      
    }
    
  } else {

  }
}


let results = [];

for(let i = 0; i < content.formats.length; i++){
  let theID = content.formats[i].id;

  let filterPoints = points.filter(({id}) => id === theID);
  let totalPoints = 0;
  let optionText = "";
  for(let j = 0; j < filterPoints.length; j++){
    // check if filterPoints[j].point has value?
    if(filterPoints[j].point) {
      totalPoints += parseInt(filterPoints[j].point);
    }
  }
  
  if(  !(Array.isArray(content.formats[i].data)) ){
    for(let k = 0; k < content.formats[i].data.options.length; k++){
      if(content.formats[i].data.options[k].max >= totalPoints && content.formats[i].data.options[k].min <= totalPoints) {
        optionText = content.formats[i].data.options[k].text;
      }
    }
  }
  results.push({ id: theID, 
                point: totalPoints, 
                unit: content.formats[i].unit, 
                description: content.formats[i].description,
                text: optionText,  
              });
}

results.sort((a,b) => a.point < b.point);
console.log(results)
</script>

<template>
  
  
  <div v-for="(result, index) in results" :key="result.id">
    <div class="bg-amber-50 sm:overflow-hidden sm:rounded-md"> 
      <div class="flex justify-between">
        <div class="px-6 py-6 font-bold">
          {{ index + 1 }}. {{ result.point }} {{ result.unit }} 
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
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                </li>
            </ul>
            </template>
        </Dropdown>
      </div>
      <div class="flex flex-col items-left px-6 pb-6">
        <p>What is {{ result.unit }}?</p>
        <p class="text-base font-medium text-slate-600"> {{ result.description }} </p>

        <p class="mt-2">Result </p>
        <p class="text-base font-medium text-slate-600">{{ result.point }}{{ result.text }} </p>
      </div>
    </div>
  </div>
</template>
<script setup>
// Import Uuid
import { v4 as uuidv4 } from "uuid";
// Import Vue
import { ref } from "@vue/reactivity";


const props = defineProps({
  schedule: Object,
  index: Number,
});

const emit = defineEmits(["change", "addSchedule", "deleteSchedule"]);

// Re-create the whole schedule data to avoid unintentional reference change
const model = ref(JSON.parse(JSON.stringify(props.schedule)));

// Get question category
const schedulePeriodical = ["day", "week", "2 weeks", "4 weeks"];
const scheduleDays = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
const timeIntervals = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];
const scheduleWeeks = [{uuid: uuidv4(), week: 1, title: "Week 1"}, {uuid: uuidv4(), week: 2, title: "Week 2"}, {uuid: uuidv4(), week: 3, title: "Week 3"}, {uuid: uuidv4(), week: 4, title: "Week 4"}];


function scheduleWeeksNum(periodical) {
  let weeksNum = 0;
  if(periodical == "day") {
    weeksNum = 0;
  }else if(periodical == "week") {
    weeksNum = 1;
  }else if(periodical == "2 weeks") {
    weeksNum = 2;
  }else {
    weeksNum = 4;
  }

  let scheduleWeek = [];
  for(let i = 0; i < weeksNum; i++) {
    scheduleWeek[i] = scheduleWeeks[i];
  }

  return scheduleWeek;
}

function sorted() {

  let data = getOptions();

  const sorter = {
    "monday": 1,
    "tuesday": 2,
    "wednesday": 3,
    "thursday": 4,
    "friday": 5,
    "saturday": 6,
    "sunday": 7
  }

  data.sort(function sortByDay(a, b) {

    if (sorter[a.day] < sorter[b.day])
        return -1

    if (sorter[a.day] > sorter[b.day])
        return 1

    if (a.day == b.day) {
      
        if (a.start_time < b.start_time)
            return -1

        if (a.start_time > b.start_time)
            return 1

        return 0
    }
    
  });

}

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
  return [ "week", "2 weeks", "4 weeks" ].includes(model.value.periodical);
}

// Add option
function addOption(num) {
  setOptions([
    ...getOptions(),
    { uuid: uuidv4(), week: num, day: "", start_time: "", end_time: "", },
  ]);
  dataChange();
  sorted();
}

// Remove option
function removeOption(op) {
  setOptions(getOptions().filter((opt) => opt !== op));
  dataChange();
}

function scheduleChange() {

  setOptions(getOptions() || []);

  dataChange();
}

// Emit the data change
function dataChange() {
  const data = model.value; 
  emit("change", data);
}

function addSchedule() {
  emit("addSchedule", props.index + 1);
}

function deleteSchedule() {
  emit("deleteSchedule", props.schedule);
}

function dateTimeChange() {
  const periodical = model.value.periodical;

  let periodicalToDay = 1;

  if(periodical === "day") {
    periodicalToDay = 1;
  }else if(periodical === "week") {
    periodicalToDay = 7;
  }else if(periodical === "2 weeks") {
    periodicalToDay = 14;
  }else {
    periodicalToDay = 28;
  }

  let recurring = model.value.recurring;

  if(recurring === 0) {
    recurring = 1;
  } 

  const endDateTime = new Date(model.value.start_datetime);
  endDateTime.setDate(endDateTime.getDate() + (periodicalToDay * recurring));
  // Month
  let month = (endDateTime.getMonth() + 1 > 9)? endDateTime.getMonth()+1 : '0'+ (endDateTime.getMonth()+1);
  // Date
  let date = (endDateTime.getDate() > 9)? endDateTime.getDate(): '0'+ endDateTime.getDate();
  // Format 2022-10-08
  model.value.end_datetime = endDateTime.getFullYear()+'-'+month+'-'+date;
  dataChange()
}

function optionWeekFilter(week) {
    return this.model.data.options.filter(option => option.week === week);
}
</script>


<template>
  <!-- Schedule index -->
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-bold">
      {{ index + 1 }}. {{  upperCaseFirst(model.periodical) }}
    </h3>

    <div class="flex items-center">
      <!-- Add new schedule -->
      <button type="button" @click="addSchedule()" class="flex items-center rounded-md text-xs py-1 px-3 mr-2 fill-white text-white bg-indigo-500 hover:bg-indigo-600">
        <box-icon class="mr-2" name='message-square-add'></box-icon>
        <span class="inline-block align-top text-base mr-2">Create Schedule</span>
      </button>
      <!--/ Add new schedule -->

      <!-- Delete schedule -->
      <button type="button" @click="deleteSchedule()" class="flex items-center rounded-md text-xs py-1 px-3 mr-2 fill-white text-white bg-red-400 hover:bg-red-500">
        <box-icon class="mr-2" name='message-square-minus'></box-icon>
        <span class="inline-block align-top text-base mr-2">Delete Schedule</span>
      </button>
      <!--/ Delete schedule -->
    </div>
  </div>

  <!--/ Question index -->
  <div class="grid gap-3 grid-cols-12">

    <!-- Schedule Periodical -->
    <div class="mt-3 col-span-3">
      <label for="schedule_periodical" class="block text-sm font-medium text-gray-700">Schedule Type</label>
      <select id="schedule_periodical" name="schedule_periodical" v-model="model.periodical" @change="scheduleChange" class="
            mt-1 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <option v-for="periodical in schedulePeriodical" :key="periodical" :value="periodical">
          {{ upperCaseFirst(periodical) }}
        </option>
      </select>
    </div>
    <!--/ Schedule Periodical -->

    <!-- Question Start Date Time -->
    <div class="mt-3 col-span-3">
      <label :for="'schedule_start_date_time' + model.data" class="block text-sm font-medium text-gray-700">Start Date</label>
      <input type="date" :name="'schedule_start_date_time' + model.data" v-model="model.start_datetime" @change="dateTimeChange"
        :id="'schedule_start_date_time' + model.data" 
        class="mt-1 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
    </div> 
    <!--/ Question Start Date Time -->

        <!-- Question Start Date Time -->
    <div class="mt-3 col-span-3">
      <label :for="'schedule_start_date_time' + model.data" class="block text-sm font-medium text-gray-700">End Date</label>
      <input type="date" :name="'schedule_start_date_time' + model.data" v-model="model.end_datetime" @change="dateTimeChange"
        :id="'schedule_start_date_time' + model.data" 
        class="mt-1 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
    </div> 
    <!--/ Question Start Date Time -->

    <!-- Question Start Date Time -->
    <div class="mt-3 col-span-3">
      <label :for="'schedule_recurring' + model.data" class="block text-sm font-medium text-gray-700">Recurring</label>
      <input type="number" :name="'schedule_recurring' + model.data" v-model="model.recurring" @change="dateTimeChange"
        :id="'schedule_recurring' + model.data" placeholder="1"
        class="mt-1 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
    </div> 
    <!--/ Question Start Date Time -->
  </div>

  

  <!-- Data -->
  <div>
    <div v-if="shouldHaveOptions()" class="mt-4">
      <div v-for="(scheduleWeek, index) in scheduleWeeksNum(model.periodical)" :key="scheduleWeek.uuid">

        <h4 class="text-sm font-base text-gray-700 mb-2 flex justify-between items-center">
          {{ scheduleWeek.title }}

          <!-- Add new option -->
          <button type="button" @click="addOption(scheduleWeek.week)" class=" flex items-center text-xs py-1 px-2 rounded-md text-white fill-white bg-indigo-500 hover:bg-indigo-600">
              <box-icon class="mr-2" name='message-square-add'></box-icon>
              <span class="inline-block align-top text-base mr-2">Create Option</span>
          </button>
          <!--/ Add new option -->
        </h4>

        <!-- Option list -->
        <div class="grid gap-3 grid-cols-12">
          <div v-for="(option, index) in optionWeekFilter(scheduleWeek.week)" :key="option.uuid" class="flex items-center mb-1 mt-3 col-span-6">
              <span class="w-6 text-sm"> {{ index + 1 }}. </span>

              
              <select v-model="option.day"  @change="dataChange" class="mt-1 ml-2 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="scheduleDay in scheduleDays" :value="scheduleDay">{{scheduleDay}}</option>
              </select>
              
              <select v-model="option.start_time"  @change="dataChange" class="mt-1 ml-2 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="timeInterval in timeIntervals" :value="timeInterval">{{timeInterval}}</option>
              </select>

              <select v-model="option.end_time"  @change="dataChange" class="mt-1 ml-2 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="timeInterval in timeIntervals" :value="timeInterval">{{timeInterval}}</option>
              </select>
        
              <!-- Delete Option -->
              <button type="button" @click="removeOption(option)" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100">
                  <box-icon class="fill-red-500" name='trash'></box-icon>
              </button>
              <!--/ Delete Option -->
            </div>
    
          </div>
        </div>
        <!--/ Option list -->
      
    </div>

    <div v-else class="mt-4">
     
        <h4 class="text-sm font-base text-gray-700 mb-2 flex justify-between items-center">
          Day

          <!-- Add new option -->
          <button type="button" @click="addOption(0)" class=" flex items-center text-xs py-1 px-2 rounded-md text-white fill-white bg-indigo-500 hover:bg-indigo-600">
              <box-icon class="mr-2" name='message-square-add'></box-icon>
              <span class="inline-block align-top text-base mr-2">Create Option</span>
          </button>
          <!--/ Add new option -->
        </h4>

        <!-- Option list -->
        <div class="grid gap-3 grid-cols-12">
          <div v-for="(option, index) in model.data.options" :key="option.uuid" class="flex items-center mb-1 mt-3 col-span-6">
              <span class="w-6 text-sm"> {{ index + 1 }}. </span>

              <select v-model="option.start_time"  @change="dataChange" class="mt-1 ml-2 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="timeInterval in timeIntervals" :value="timeInterval">{{timeInterval}}</option>
              </select>

              <select v-model="option.end_time"  @change="dataChange" class="mt-1 ml-2 block w-full py-2 px-3 border border-gray-400 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="timeInterval in timeIntervals" :value="timeInterval">{{timeInterval}}</option>
              </select>
        
              <!-- Delete Option -->
              <button type="button" @click="removeOption(option)" class="h-6 w-6 rounded-full flex items-center justify-center border border-transparent transition-colors hover:border-red-100">
                  <box-icon class="fill-red-500" name='trash'></box-icon>
              </button>
              <!--/ Delete Option -->
            </div>
    
        </div>
        <!--/ Option list -->
      
    </div>

  </div>
  <!--/ Data -->

</template>

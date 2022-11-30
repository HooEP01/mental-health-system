<script setup>
// Import Uuid
import { v4 as uuidv4 } from "uuid";
import { ref, toRef } from "vue";

// Props
const { schedules, index, modelValue } = defineProps({
    schedules: Object,
    index: Number,
    modelValue: [String, Array],
});

const sorter = {
    "monday": 1,
    "tuesday": 2,
    "wednesday": 3,
    "thursday": 4,
    "friday": 5,
    "saturday": 6,
    "sunday": 7
}

const periodicalSorter = {
    "day": 1,
    "week": 7,
    "2 weeks": 14,
    "4 weeks": 28,
}

// variable
let models = [];

// schedules loop
for (let schedule of schedules) {

    // periodical value (days)
    let periodical = periodicalSorter[schedule.periodical];

    // recuring value
    let recurring = schedule.recurring;

    // start date time
    let startDateTime = new Date(schedule.start_datetime);

    // end date time
    let endDateTime = new Date(schedule.end_datetime);

    // recurring
    for (let i = 0; i < recurring; i++) {

        for (let data of schedule.data.options) {

            // data's start time
            let startTime = data.start_time;

            // data's end time
            let endTime = data.end_time;

            // current start time
            let currentStartTime = new Date( formatDate(startDateTime) + " " + startTime );

            // current end time
            let currentEndTime = new Date( formatDate(endDateTime) + " " + endTime );

            // start date's day (monday - sunday)
            let currentStartDay = currentStartTime.getDay();

            // data's day
            let currentDataDay = sorter[data.day];

            currentStartTime.setDate(currentStartTime.getDate() + currentDataDay - currentStartDay);

            // get start hours: format 11:00 => 11
            let currentStartHour = currentStartTime.getHours();
            // get end hours
            let currentEndHour = currentEndTime.getHours();


            // Week (1,2,3,4)
            let week = data.week;

            // run hours each with 1 hour interval
            for (let j = currentStartHour; j < currentEndHour; j++) {

                if (week != 0) {
                    currentStartTime.setDate(currentStartTime.getDate() + ((week - 1) * 7))
                }

                // increment time by 1
                currentStartTime.setHours(currentStartTime.getHours() + 1);

                // get the date time
                let theDatetime = new Date(currentStartTime);
                let theDate = formatDate(theDatetime);
                let theTime = formatTime(theDatetime);

                // push value to models
                models.push({
                    uuid: uuidv4(),
                    date: theDate,
                    time: theTime,
                    day: data.day,
                });
            }

        }

        // each recurring + periodical day
        startDateTime.setDate(startDateTime.getDate() + periodical);

    }
}

console.log(models)

const result = [...new Map(models.map((m) => [m.date, m])).values()];



console.log('result', result)

function formatDate(theDateTime) {
    // Month
    let month = (theDateTime.getMonth() + 1 > 9)? theDateTime.getMonth()+1 : '0'+ (theDateTime.getMonth()+1);
    // Date
    let date = (theDateTime.getDate() > 9)? theDateTime.getDate(): '0'+ theDateTime.getDate();
    // Format 2022-10-08
    return theDateTime.getFullYear()+'-'+month+'-'+date;
}

function formatTime(theDateTime) {
    let hour = (theDateTime.getHours() > 9)? theDateTime.getHours() : '0'+ (theDateTime.getHours());
    return hour+":00";
}


function filterDate(arr) {
    
    return models.filter((opt) => opt.date === arr);
}



function sortDate() {

}

function sortTime() {

}

function selectedModel(model) {
    emits("update:modelValue", model);
}

// Emits
const emits = defineEmits(["update:modelValue"]);

</script>


<template>
    <fieldset class="mb-4">
        <div class="md:grid md:grid-cols-5 md:gap-3">
            <div v-for="r in result">
                <div class="">
                    <p class="text-xl font-bold text-indigo-500 text-center">{{ r.day.toUpperCase() }}</p> 
                    <p class="text-sm text-slate-600 text-center">{{ r.date }}</p>
                </div>
                <div class="md:col-span-1 relative">
                    <div v-for="model in filterDate(r.date)" :key="model.uuid" class="mt-2">
                        <button type="button" @click="selectedModel(model)"  class="flex items-center rounded-md text-xs py-1 px-3 mx-auto fill-black hover:fill-white text-gray-900 hover:text-white bg-transparent hover:bg-indigo-500">
                            <!-- <box-icon class="mr-2" name='message-square-add'></box-icon> -->
                            <span class="inline-block align-top text-lg mr-2">{{ model.time }}</span>
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </fieldset>
</template>
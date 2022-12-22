<script setup>
// Import Uuid
import Dropdown from '@/Components/Dropdown.vue';
import { v4 as uuidv4 } from "uuid";
import { ref, toRef } from "vue";

// Props
const { schedules, appointments, index, events, modelValue } = defineProps({
    appointments: Object,
    schedules: Object,
    events: Object,
    index: Number,
    modelValue: [String, Array],
});

const sorter = {
    "Monday": 1,
    "Tuesday": 2,
    "Wednesday": 3,
    "Thursday": 4,
    "Friday": 5,
    "Saturday": 6,
    "Sunday": 7
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

            let session_length = parseInt(events.session_length);
            let session_interval = session_length / 60;
  

            // run hours each with 1 hour interval
            for (let j = currentStartHour; j < currentEndHour; j = j + session_interval) {

                if (week != 0) {
                    currentStartTime.setDate(currentStartTime.getDate() + ((week - 1) * 7))
                }


                // get the date time
                let theDatetime = new Date(currentStartTime);
                let theEndDateTime = new Date(currentStartTime);
                theEndDateTime.setMinutes(theEndDateTime.getMinutes() + session_length);

                let theDate = formatDate(theDatetime);
                let theTime = formatTime(theDatetime);
                let theEndTime = formatTime(theEndDateTime);

                // push value to models
                models.push({
                    uuid: uuidv4(),
                    start_date: theDate,
                    end_date: theDate,
                    start_time: theTime,
                    end_time: theEndTime,
                    day: data.day,
                });

                // increment time by 1

                if(session_length % 60 == 0) {
                    currentStartTime.setHours(currentStartTime.getHours() + 1);   
                }else{
                    currentStartTime.setMinutes(currentStartTime.getMinutes() + session_length);
                }
                
            }

        }

        // each recurring + periodical day
        startDateTime.setDate(startDateTime.getDate() + periodical);

    }
}

let count = ref(0);
let result = ref([]);
let total = [...new Map(models.map((m) => [m.start_date, m])).values()];


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
    let minutes = (theDateTime.getMinutes() > 9)? theDateTime.getMinutes(): '0'+ (theDateTime.getMinutes());
    return hour+":"+minutes;
}


function filterDate(arr) {
    return models.filter((opt) => opt.start_date === arr);

}

function filterResult(int) {
    let resultStart = 0 + (4*int);
    let resultEnd = 4 + (4*int);
    result = [...new Map(models.map((m) => [m.start_date, m])).values()].slice(resultStart, resultEnd);
    return result.sort((a,b) => (a.start_date > b.start_date) ? 1: ((b.last_nom > a.last_nom) ? -1 : 0));
}


function filterAppointment(model) {
    let app = appointments.find(( opt ) => opt.event_id === events.id && (opt.start_time).slice(0,-3) === model.start_time && opt.start_date === model.start_date);
    return (app)? true: false;
}

function groupAppointment(model) {
    
    if(events.attendance > 1) {
        let app = appointments.filter(( opt ) => opt.event_id === events.id && (opt.start_time).slice(0,-3) === model.start_time && opt.start_date === model.start_date);

        if(app.length == events.attendance) {
            return 0;
        }
        
        return app.length;
    }
    return 0;
}

function filterAppointmentEvents(model) {
    let app = appointments.find((opt) => {
        let opt_start_time = (opt.start_time).slice(0,-3);
        let opt_end_time = (opt.end_time).slice(0,-3)
        if(opt.event_id != events.id) {
            if(opt.start_date == model.start_date) {
                if(opt_start_time >= model.start_time && opt_end_time <= model.end_time || 
                   opt_start_time <= model.start_time && opt_end_time >= model.end_time || 
                   opt_start_time <= model.start_time && opt_end_time < model.end_time && opt_end_time > model.start_time || 
                   opt_start_time > model.start_time && opt_end_time >= model.end_time && opt_start_time < model.end_time
                   ) {
                    return opt;
                }
            }
        }
    });
 
    return (app)? true: false;
} 

function selectedModel(theModel) {
    emits("update:modelValue", theModel);
}

// Emits
const emits = defineEmits(["update:modelValue"]);

</script>


<template>
    <div class="bg-slate-50 sm:overflow-hidden sm:rounded-md"> 
        <div class="flex justify-between">
            <div class="px-6 py-6 font-bold">
                Session Length: {{ events.session_length }} minutes <br/>
                <div v-if="events.attendance != 1">
                    Attendance Per Session: {{ events.attendance }} Max
                </div>
            </div>
            <div></div>
            <div class="place-content-end px-6 py-4">
                <button v-if="count != 0" @click="count--" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                    <span class="sr-only">Open dropdown</span>
                    <box-icon name='chevron-left'></box-icon>
                </button>
                <button v-else class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                    <span class="sr-only">Open dropdown</span>
                    <box-icon name='chevron-left'></box-icon>
                </button>
                <button v-if="total.length / 5 > count + 1" @click="count++" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                    <span class="sr-only">Open dropdown</span>
                    <box-icon name='chevron-right'></box-icon>
                </button>
                <button v-else class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                    <span class="sr-only">Open dropdown</span>
                    <box-icon name='chevron-right'></box-icon>
                </button>
            </div>
        </div>

        <fieldset class="mb-4">
            <div class="md:grid md:grid-cols-4 md:gap-3">
                <div v-for="r in filterResult(count)">
                    <div class="">
                        <p class="text-xl font-bold text-indigo-500 text-center">{{ r.day.toUpperCase() }}</p> 
                        <p class="text-sm text-slate-600 text-center">{{ r.start_date }}</p>
                    </div>
                    <div class="md:col-span-1 relative">
                        <div v-for="model in filterDate(r.start_date)" :key="model.uuid" class="mt-2">
                            <div v-if="filterAppointment(model)">
                                <div v-if="groupAppointment(model) == 0">
                                    <button type="button" class="flex items-center rounded-md text-xs py-1 px-3 mx-auto fill-white text-white bg-red-500">
                                        <box-icon class="mr-2" name='time'></box-icon>
                                        <span class="inline-block align-top text-lg mr-2">{{ model.start_time }} - {{ model.end_time }}   </span>
                                    </button>
                                </div>
                                <div v-else class="flex">
                                    <button v-if="(modelValue.uuid === model.uuid)" type="button" @click="selectedModel(model)"  class="items-center rounded-md text-xs py-1 px-3 mx-auto fill-white text-gray-900 text-white bg-emerald-700">
                                        <box-icon class="mr-2" name='time'></box-icon>
                                        <span class="inline-block align-top text-lg mr-2">{{ model.start_time }} - {{ model.end_time }}  </span>
                                        <p class="block">{{ groupAppointment(model) }} Attend</p>
                                    </button>
                                    <button v-else type="button" @click="selectedModel(model)" class="items-center rounded-md text-xs py-1 px-3 mx-auto fill-white text-white bg-emerald-500">
                                        <box-icon class="mr-2" name='time'></box-icon>
                                        <span class="inline-block align-top text-lg mr-2">{{ model.start_time }} - {{ model.end_time }}  </span>
                                        <p> {{ groupAppointment(model) }} Attend</p>
                                    </button>
                                </div>
                            </div>
                            <div v-else-if="filterAppointmentEvents(model)">
                                <button type="button" class="flex items-center rounded-md text-xs py-1 px-3 mx-auto fill-white text-white bg-slate-500">
                                    <box-icon class="mr-2" name='time'></box-icon>
                                    <span class="inline-block align-top text-lg mr-2">{{ model.start_time }} - {{ model.end_time }}   </span>
                                </button>
                            </div>
                            <div v-else>
                                <button v-if="(modelValue.uuid === model.uuid)" type="button" @click="selectedModel(model)"  class="flex items-center rounded-md text-xs py-1 px-3 mx-auto fill-white text-gray-900 text-white bg-indigo-500">
                                    <box-icon class="mr-2" name='time'></box-icon>
                                    <span class="inline-block align-top text-lg mr-2">{{ model.start_time }} - {{ model.end_time }}  </span>
                                </button>
                                <button v-else type="button" @click="selectedModel(model)"  class="flex items-center rounded-md text-xs py-1 px-3 mx-auto fill-black hover:fill-white text-gray-900 hover:text-white bg-transparent hover:bg-indigo-500">
                                    <box-icon class="mr-2" name='time'></box-icon>
                                    <span class="inline-block align-top text-lg mr-2">{{ model.start_time }} - {{ model.end_time }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</template>
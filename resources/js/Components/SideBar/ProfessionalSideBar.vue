<script setup>
    // import inertia file
    import BtnLink from  '@/Components/BtnLink.vue';
</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <BtnLink :href="route('contents.index')" :active="route().current('contents.index')">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Content</span>
        </BtnLink>
    </li>

    <!-- event link -->
    <li class="flow-root">
        <BtnLink :href="route('events.index')" :active="route().current('events.index')">
            <box-icon class="mr-2" name='calendar-event'></box-icon>
            <span class="inline-block align-top">Event</span>
        </BtnLink>
    </li>

    <!-- appointment link -->
    <li class="flow-root">
        <BtnLink :href="route('appointments.index')" :active="route().current('appointments.index')">
            <box-icon class="mr-2" name='calendar-star'></box-icon>
            <span class="inline-block align-top">Appointment</span>
        </BtnLink>
    </li>

    <!-- answer link -->
    <li class="flow-root">
        <BtnLink :href="route('answers.index')" :active="route().current('answers.index')">
            <box-icon class="mr-2" name='book-heart'></box-icon>
            <span class="inline-block align-top">Answer</span>
        </BtnLink>
    </li>

    <!-- default slot -->
    <slot/>
    
</template>


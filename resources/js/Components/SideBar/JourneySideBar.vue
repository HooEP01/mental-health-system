<script setup>
    // import inertia file
    import BtnLink from  '@/Components/BtnLink.vue';
</script>

<template>
    <!-- answer link -->
    <li class="flow-root">
        <BtnLink :href="route('answer.index')" :active="route().current('answer.index')">
            <box-icon class="mr-2" name='book-heart'></box-icon>
            <span class="inline-block align-top">Answer</span>
        </BtnLink>
    </li>

    <!-- appointment link -->
    <li class="flow-root">
        <BtnLink :href="route('appointment.index')" :active="route().current('appointment.index')">
            <box-icon class="mr-2" name='calendar-star'></box-icon>
            <span class="inline-block align-top">Appointment</span>
        </BtnLink>
    </li>

    <!-- default slot -->
    <slot/>
    
</template>


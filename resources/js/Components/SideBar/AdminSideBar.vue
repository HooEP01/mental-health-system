<script setup>
    // import inertia file
    import BtnLink from  '@/Components/BtnLink.vue';
</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <BtnLink :href="route('contents_view.index')" :active="route().current('contents_view.index')">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Content</span>
        </BtnLink>
    </li>

    <!-- event link -->
    <li class="flow-root">
        <BtnLink :href="route('events_view.index')" :active="route().current('events_view.index')">
            <box-icon class="mr-2" name='calendar-event'></box-icon>
            <span class="inline-block align-top">Event</span>
        </BtnLink>
    </li>

    <!-- appointment link -->
    <li class="flow-root">
        <BtnLink :href="route('appointments_view.index')" :active="route().current('appointments_view.index')">
            <box-icon class="mr-2" name='calendar-star'></box-icon>
            <span class="inline-block align-top">Appointment</span>
        </BtnLink>
    </li>

    <!-- answer link -->
    <li class="flow-root">
        <BtnLink :href="route('answers_view.index')" :active="route().current('answers_view.index')">
            <box-icon class="mr-2" name='book-heart'></box-icon>
            <span class="inline-block align-top">Report</span>
        </BtnLink>
    </li>

    <!-- payment link -->
    <li class="flow-root">
        <BtnLink :href="route('payments_view.index')" :active="route().current('payments_view.index')">
            <box-icon class="mr-2" name='transfer-alt'></box-icon>
            <span class="inline-block align-top">Payments</span>
        </BtnLink>
    </li>

    <!-- professional link -->
    <li class="flow-root">
        <BtnLink :href="route('users_view.index')" :active="route().current('users_view.index')">
            <box-icon class="mr-2" type='solid' name='user-detail'></box-icon>
            <span class="inline-block align-top">Users</span>
        </BtnLink>
    </li>

    <!-- role link -->
    <li class="flow-root">
        <BtnLink :href="route('roles_view.index')" :active="route().current('roles_view.index')">
            <box-icon class="mr-2" name='briefcase-alt-2'></box-icon>
            <span class="inline-block align-top">Role</span>
        </BtnLink>
    </li>

    <!-- permission link -->
    <li class="flow-root">
        <BtnLink :href="route('permissions_view.index')" :active="route().current('permissions_view.index')">
            <box-icon class="mr-2" name='layer'></box-icon>
            <span class="inline-block align-top">Permission</span>
        </BtnLink>
    </li>

    <!-- default slot -->
    <slot/>
    
</template>


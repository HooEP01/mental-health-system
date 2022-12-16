<script setup>

// import vue 
import { computed } from 'vue';

// import inertia
import { Link, usePage } from '@inertiajs/inertia-vue3';
import BtnLink from  '@/Components/BtnLink.vue';
// get permission list set from Middleware/HandleInertiaRequests.php
const can = computed(() => usePage().props.value.auth.can)
</script>

<template>
    <!-- user profile link -->
    <li class="flow-root">
        <BtnLink :href="route('payment.index')" :active="route().current('payment.index')">
        <box-icon class="mr-2" name='transfer-alt'></box-icon>
        <span class="inline-block align-top">Payment</span>
        </BtnLink>
    </li>
    <!-- user professional link -->

    <!-- professional profile link -->
    <li class="flow-root" v-if="can.payments">
        <BtnLink :href="route('payments.index')" :active="route().current('payments.index')">
        <box-icon class="mr-2" name='wallet-alt'></box-icon>
        <span class="inline-block align-top">Professional Payment</span>
        </BtnLink>
    </li>

</template>
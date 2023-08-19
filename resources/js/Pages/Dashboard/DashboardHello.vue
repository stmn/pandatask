<script setup>
const props = defineProps({
    hello: String
});
</script>

<template>
    <b>{{ hello }}</b>, {{ $page.props.auth.user.first_name }}!
    <i class="fa-regular fa-face-smile-wink ml-1"/>
</template>

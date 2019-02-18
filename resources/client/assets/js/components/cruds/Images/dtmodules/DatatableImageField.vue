<template>
    <div v-html="row.image_link"></div>
</template>


<script>
    export default {
        props: ['row'],
    }
</script>

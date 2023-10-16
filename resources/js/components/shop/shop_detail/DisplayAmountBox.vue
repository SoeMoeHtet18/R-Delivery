<template>
      <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
        <h5 class="my-4 label">{{ title }}</h5>
        <hr class="border-main">
        <h5 class="my-6 label-count">{{this.formatWithNumberingSystem(amount)}} MMK</h5>
    </div>
</template>
<script>
export default {
    props: {
        title: String,
        amount: String,
    },
    methods: {
        formatWithNumberingSystem(number, decimal_place = 2) {
            if(number) {
                return parseFloat(number).toFixed(decimal_place).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            } else {
                return '';
            }
        },
    },
}
</script>
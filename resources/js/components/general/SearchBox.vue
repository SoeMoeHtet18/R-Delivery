<template>
    <DxTextBox
        ref="search"
        class="search-btn"
        width="280px"
        height="34px"
        placeholder="Search"
        :buttons="[
            {
                location: 'before',
                name: 'searchButton',
                options: searchButton,
            },
        ]">
    </DxTextBox>
</template>
<script>
import { DxTextBox, DxButton as DxTextBoxButton } from 'devextreme-vue/text-box';

const searchButton = {
    icon: '/images/icons/search.svg',
    type: 'default',
};

export default {
    components: {
        DxTextBox,
        DxTextBoxButton
    },
    data() {
        return {
            searchButton: searchButton
        };
    },
    mounted() {
        this.$refs.search.$el
            .querySelector(".dx-texteditor-input")
            .addEventListener("input", this.getDataBySearch);
    },
    methods: {
        getDataBySearch(event) {
            const input = event.target.value;
            this.$emit('search', {
                input: input
            });
        }
    }
}

</script>
<style>
.dx-placeholder::before {
    padding: 6px 9px 9px !important;
}

.search-btn {
    border-radius: 20px !important;
    border-color: #116A5B !important;
}

.search-btn .dx-button-mode-contained.dx-button-default {
    margin-left: 11px;
    border: none !important;
    background: transparent !important;
}
</style>
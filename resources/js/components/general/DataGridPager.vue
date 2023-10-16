<template>
    <div>
        <DxPaging :page-size="10" />
        <DxPager
            :visible="true"
            :allowed-page-sizes="pageSize"
            display-mode="compact"
            :show-page-size-selector="true"
            :show-info="true"
            :show-navigation-buttons="true"
        />
        <DxScrolling
            mode="standard"
            column-rendering-mode="virtual"
            :use-native="false"
            :scroll-by-content="true"
            :scroll-by-thumb="true"
            show-scrollbar="never"
        />
    </div>
</template>

<script>
import { DxPager, DxPaging, DxScrolling } from 'devextreme-vue/data-grid';

export default {
    props: {
        pageSize: Array,
    },
    components: {
        DxPager,
        DxPaging,
        DxScrolling,
    },
};
</script>
  
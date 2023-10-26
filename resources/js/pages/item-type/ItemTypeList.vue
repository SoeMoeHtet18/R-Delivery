<template>
    <div class="px-9 pt-2 pb-1 font-lato">
        <div style="height: 19vh;">
            <!-- page title -->
            <h1 class="page-title mb-7">ITEM TYPE</h1>
            <div class="flex mb-5">
                <!-- search box -->
                <SearchBox @search="getItemTypes"/>
                <!-- create new item type btn -->
                <button
                    class="bg-main text-white rounded-sm ml-5 px-4 pb-0.5" 
                    @click="createItemType"
                >Create Item Type</button>
                <item-type-create v-if="isCreate"
                    @close="close"
                    @refreshData="getItemTypes"
                ></item-type-create>
            </div>
        </div>
        <dx-data-grid
            :data-source="itemTypes"
            :customize-columns="customizeColumns"
            class="custom-data-grid"
            :columnAutoWidth="true"
            ref="itemTypeDataGrid"
            :style="{ 'height': '79vh' }"
        >
            <DxColumn 
                data-field="index"
                caption="ID"
            />
            <DxColumn
                data-field="name"
                caption="ITEM TYPE NAME"
                cell-template="nameTemplate"
            />
            <template #nameTemplate="{ data }">
                <span v-if="!data.data.isEdit">{{ data.data.name }}</span>
                <DxTextBox v-if="data.data.isEdit"
                    v-model:value = "data.data.editName"
                    class="detail-edit-input"
                />
            </template>
            <DxColumn
                caption=""
                cell-template="editColumn"
            />
            <template #editColumn="{ data }">
                <div class="flex justify-center">
                    <Icon v-if="!data.data.isEdit" icon="ph:pencil" color="#116a5b" width="20"
                        class="mr-3" @click="editItem(data.data)"/>
                    <Icon v-if="!data.data.isEdit" icon="ph:trash-light" color="#e33b3b" width="20" 
                        @click="deleteItem(data.data)"/>
                    <Icon v-if="data.data.isEdit" icon="fluent-mdl2:cancel" color="#e33b3b" width="16"  
                        class="mr-3" @click="cancelEdit(data.data)"/>
                    <Icon v-if="data.data.isEdit" icon="cil:save" color="#116a5b" width="16" 
                        @click="saveItem(data.data)" />
                </div>
            </template>
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
        </dx-data-grid>

        <DxPopup v-if="isLoading" :drag-enabled="false">
            <div class="popup-content">
                <loader />
            </div>
        </DxPopup>
        <DxPopup v-if="isSuccess" :drag-enabled="false">
            <div class="popup-content">
                <success-pop-up />
            </div>
        </DxPopup>
    </div>
</template>
<script>
import { Icon } from '@iconify/vue';
import SearchBox from '../../components/general/SearchBox.vue';
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxColumn } from 'devextreme-vue/data-grid';
import  DxTextBox from 'devextreme-vue/text-box';
import ItemTypeCreate from './ItemTypeCreate.vue';

const customizeColumns = (columns) => {
    columns[0].width = 117;
    columns[2].width = 205;
};

export default {
    components: {
        Icon,
        SearchBox,
        DxDataGrid,
        DxPager,
        DxPaging,
        DxScrolling,
        DxColumn,
        DxTextBox,
        ItemTypeCreate
    },
    data() {
        return {
            itemTypes: [],
            isCreate: false,
            customizeColumns: customizeColumns,
            isLoading: false,
            isSuccess: false
        }
    },
    methods: {
        async deleteItem(item) {
            this.isLoading = true;

            const csrfToken = document.head.querySelector('meta[name="_token"]').content;

            const response = await fetch(`/api/item-types/${item.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            });
            const encodedData = await response.json();
            
            setTimeout(() => {
                this.isLoading = false;
                item.isEdit = false;
                if(encodedData.status.toLowerCase() == 'success') {
                    this.isSuccess = true;
                    setTimeout(() => {
                        this.isSuccess = false;
                        this.closePopup();
                    }, 1000);
                    this.getItemTypes();
                }
            }, 1000);
        },
        async saveItem(item) {
            this.isLoading = true;

            const apiUrl = `/api/item-types/${item.id}`;
            const requestData = {
                'name': item.editName,
            };

            const csrfToken = document.head.querySelector('meta[name="_token"]').content;

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });
            const encodedData = await response.json();
            
            setTimeout(() => {
                this.isLoading = false;
                item.isEdit = false;
                if(encodedData.status.toLowerCase() == 'success') {
                    this.isSuccess = true;
                    setTimeout(() => {
                        this.isSuccess = false;
                        this.closePopup();
                    }, 1000);
                    this.getItemTypes();
                }
            }, 1000);
        },
        editItem(item) {
            item.isEdit = true;
        },
        cancelEdit(item) {
            item.editName = item.name;
            item.isEdit = false;
        },
        async getItemTypes(target) {
            let apiUrl = '/api/item-types';
            if(target) {
                apiUrl += `?search=${target.input}`;
            }
            const response = await fetch(apiUrl);
            const data = await response.json();

            const formattedData = data.data.map((itemType, index) => ({
                'id': itemType.id,
                'name': itemType.name,
                'index': index + 1,
                'isEdit': false,
                'editName': itemType.name
            }));
            this.itemTypes = formattedData;
        },
        createItemType() {
            this.isCreate = true;
        },
        close() {
            this.isCreate = false;
        }
    },
    mounted() {
        if(this.itemTypes.length == 0) {
            this.getItemTypes();
        }
    }
}
</script>
<style>
    .detail-edit-input .dx-texteditor-input {
        min-height: unset;
        height: 26px;
        text-align: center;
    }
</style>
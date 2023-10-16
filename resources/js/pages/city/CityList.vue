<template>
    <div class="px-9 py-2 font-lato city-list">
        <h1 class="page-title mb-7">CITY</h1>
        <div class="flex mb-5">
            <!-- search box -->
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
           
            <!-- create new shop btn -->
            <button class="bg-main text-white rounded-sm px-4 ms-3" @click="clickCreateBtn">Create New City</button>
        </div>
        <!-- table list view -->
        <div id="list-view-container">
            <!-- table -->
            <dx-data-grid
                :data-source="gridData"
                class="custom-data-grid"
                :columnAutoWidth="true"
                ref="myDataGrid"
            >
                <DxColumn 
                    data-field="index"
                    caption="ID"
                    :allow-editing="false"
                />
                <DxColumn
                    caption="CITY NAME"
                    cell-template="cityNameColumn"
                    class="city-name"
                />
                <template #cityNameColumn="{ data }">
                    <div>
                        <span v-if="!data.data.isEdit">{{ data.data.cityName }}</span>
                        <DxTextBox v-if="data.data.isEdit"
                            v-model:value = "data.data.changedCityName"
                            class="center-text-box"
                        />
                    </div>
                </template>
                <DxColumn
                    caption=""
                    cell-template="editColumn"
                />
                <template #editColumn="{ data }">
                    <div>
                        <iconify-icon v-if="!data.data.isEdit" icon="ph:pencil" width="20" style="color: #116a5b;" @click="clickEditBtn(data.data)" class="me-2"></iconify-icon>
                        <iconify-icon v-if="!data.data.isEdit" icon="ph:trash-light" width="20" style="color: #E33B3B;"></iconify-icon>
                        <iconify-icon v-if="data.data.isEdit" icon="fluent-mdl2:cancel" width="20" style="color: #E33B3B;" @click="closeEditBtn(data.data)" class="me-2"></iconify-icon>
                        <iconify-icon v-if="data.data.isEdit" icon="cil:save" width="20" style="color: #116a5b;" flip="horizontal" @click="saveEditData(data.data)"></iconify-icon>
                    </div>
                </template>
                <DxPaging :page-size="10"/>
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
                    :use-native="false"
                    :scroll-by-content="true"
                    :scroll-by-thumb="true"
                    show-scrollbar="never" 
                /> 
            </dx-data-grid>
        </div>

        <DxPopup
            v-model:visible="createPopupVisible"
            :drag-enabled="false"
            :hide-on-outside-click="true"
            :show-title="true"
            :width="600"
            :height="200"
            container=".dx-viewport"
            title="CREATE CITY"
            class="city-create-popup">
            <!-- ... -->
                
                <div class="city-create-form">
                    <div>
                        <p class="mb-1">CITY NAME</p>
                        <DxTextBox 
                        v-model="city.name" 
                        :isValid="isNameValid"
                        :onFocusOut="validateName"/>
                        <span v-if="!isNameValid" class="validation-error mt-1">City Name is required.</span>
                    </div>
                    
                    <div class="mt-5 flex justify-end">
                        <DxButton :width="100" text="Cancel" styling-mode="outlined"
                            style="margin-right: 10px; border-color: #116a5b; color: #116a5b;" @click="closeCreatePopUp"/>
                        <DxButton :width="100" text="Save" styling-mode="contained" style="background-color: #116a5b; color: #ffff;"
                            @click="saveData" />
                    </div>
                </div>
        </DxPopup>
        <DxPopup
            v-model:visible="isloading"
            :drag-enabled="false"
            :hide-on-outside-click="true"
            :show-close-button="false"
            :show-title="false"
            :width="300"
            :height="100"
            container=".dx-viewport">
            <div class="loading-popup-content">
                <div class="spinner"></div>
                <span class="loading-text">Loading, please wait...</span>
            </div>
        </DxPopup>
        <DxPopup 
            v-model:visible="isShowSuccess"
            :hide-on-outside-click="true"
            :drag-enabled="false"
            :show-close-button="false"
            :show-title="false"
            :width="300"
            :height="100"
            container=".dx-viewport">
            <div class="loading-popup-content">
                <iconify-icon icon="gg:check-o" style="color: #2ab936; margin-right: 10px;" width="24"></iconify-icon>
                <span class="loading-text">Done successfully.</span>
            </div>
        </DxPopup>
    </div>
</template>
<script>
import  DxTextBox from 'devextreme-vue/text-box';
import DxSelectBox from 'devextreme-vue/select-box';
import DxDateBox from 'devextreme-vue/date-box';
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxSearchPanel, DxColumn, DxEditing } from 'devextreme-vue/data-grid';
import { DxPopup, DxPosition, DxToolbarItem } from 'devextreme-vue/popup';
import DxButton from 'devextreme-vue/button';
import { DxLoadPanel } from 'devextreme-vue/load-panel';

const searchButton = {
    icon: '/images/icons/search.svg',
    type: 'default',
};

export default {
    components: {
        DxTextBox,
        DxButton,
        DxSelectBox,
        DxDateBox,
        DxDataGrid,
        DxPager,
        DxPaging,
        DxScrolling,
        DxPopup,
        DxToolbarItem,
        DxLoadPanel,
        DxColumn,
        DxEditing
    },
    data() {
        return {
            popupHeight: window.innerHeight,
            searchButton: searchButton,
            gridData: [],
            pageSize: [10, 20, 50, 100],
            pageIndex: 0,
            createPopupVisible: false,
            city: {
                name: null,
            },
            isNameValid: true,
            isloading: false,
            isShowSuccess: false,
        };
    },
    methods: {
        clickCreateBtn() {
            this.createPopupVisible = true;
        },
        closeCreatePopUp() {
            this.createPopupVisible = false;
        },
        clickEditBtn(data) {
            console.log(data);
            data.isEdit = true;
        },
        closeEditBtn(data) {
            console.log(data);
            data.isEdit = false;
            data.changedCityName = data.cityName;
        },
        // updateEditedValue(id, newVal) {
        //     const index = this.gridData.find((item) => item.id === id);
        //     console.log(index);
        // },
        saveEditData(data) {
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = data;
            this.isloading = true;
            console.log(formData);

            fetch("/api/update-city-data", {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                }),
                body: JSON.stringify({
                    data: formData,
                }),
            })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if(data.status == 'success') {
                    this.isloading = false;
                    this.isShowSuccess = true;
                    setTimeout(() => {
                        this.isShowSuccess = false;
                        this.createPopupVisible = false;
                    }, 2000);
                    data.isEdit = false;
                    this.getCityTableData();
                }
            })
            .catch((error) => {
                return error;
            });
        },
        
        async getDataBySearch(event) {
            const input = event.target.value;
            const response = await fetch(`/api/cities?search=${input}`);
            this.fetchData(response);
        },
        async getCityTableData() {
            const response = await fetch(`/api/cities`);
            this.fetchData(response);
        },
        async fetchData(response) {
            try {
                const data = await response.json();
                
                const formattedData = data.data.map((city, index) => ({
                    'id': city.id,
                    'index': index + 1,
                    'cityName': city.name,
                    'isEdit': false,
                    'changedCityName': city.name,
                }));

                this.gridData = formattedData;
                console.log('grid table data => ', this.gridData);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        saveData() {
            console.log('save user data');
            
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = this.city;
            this.isloading = true;
            console.log(formData);

            fetch("/api/save-city-data", {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                }),
                body: JSON.stringify({
                    data: formData,
                }),
            })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if(data.status == 'success') {
                    this.isloading = false;
                    this.isShowSuccess = true;
                    setTimeout(() => {
                        this.isShowSuccess = false;
                        this.createPopupVisible = false;
                    }, 2000);
                    this.city.name = null;
                    this.getCityTableData();
                }
            })
            .catch((error) => {
                return error;
            });
        },
        validateName() {
            if(this.city.name == null) {
                this.isNameValid = false;
            } else {
                this.isNameValid = true;
            }
        },
    },
    mounted() {
        this.getCityTableData();
        this.$refs.search.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.getDataBySearch
        );
    },
}
</script>
<style>
.city-list .dx-datagrid .dx-column-lines > td {
    border-left: none;
    border-right: none;
}
.city-list .center-text-box .dx-texteditor-input{
  text-align: center;
}

.city-list .custom-data-grid .dx-header-row td {
    text-align: center !important;
    border-left: none;
    border-right: none;
    border-bottom: 1px solid #D9D9D9;
    padding: 10px 0 !important;
    text-transform: capitalize;
    font-family: Lato;
    color: black;
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 18px;
}
</style>
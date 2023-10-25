<template>
    <div class="px-9 py-2 font-lato branch-list">
        <div id="filterContentContainer"
            ref="filterContentContainer"
        >
             <!-- page title -->
            <h1 class="page-title mb-7">BRANCH</h1>
            <div class="flex mb-3">
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
            
                <iconify-icon icon="prime:filter-fill" width="30" class="mx-3" @click="toggleSearch"></iconify-icon>
                <!-- create new shop btn -->
                <button class="bg-main text-white rounded-sm px-4 ms-3" @click="clickCreateBtn">Create New Branch</button>
            </div>
            <!-- filter container -->
            <div id="filter-container" v-if="isToggleSearch">
                <div class="flex">
                    <!-- township filter -->
                    <DxSelectBox
                        class="search-box ml-21"
                        :items="townshipList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="Township"
                        ref="township"
                        :onValueChanged=getDataByTownship
                        width="204px"
                    />
                    <!-- city filter -->
                    <DxSelectBox
                        class="search-box ml-9"
                        :items="cityList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="City"
                        ref="city"
                        :onValueChanged=getDataByCity
                        width="204px"
                    />
                </div>
            </div>
        </div>

        <!-- table list view -->
        <div id="list-view-container">
            <!-- table -->
            <dx-data-grid
                :data-source="gridData"
                class="custom-data-grid"
                :columnAutoWidth="true"
                ref="myDataGrid"
                :style="{ 'min-height': containerHeight, 'max-height': containerHeight }"
                @row-click="directToBranchDetail"
            >
                <DxColumn 
                    data-field="index"
                    caption="ID"
                />
                <DxColumn 
                    data-field="branchName"
                    caption="BRANCH NAME"
                />
                <DxColumn 
                    data-field="city"
                    caption="CITY"
                    cell-template="cityTemplate"
                />
                <template #cityTemplate="{ data }">
                    <a class="hover-text" :href="`/cities/${data.data.cityId}`">{{ data.data.city }}</a>
                </template>
                <DxColumn
                    data-field="townships"
                    caption="TOWNSHIP"
                    cell-template="townshipTemplate"
                />
                <template #townshipTemplate="{ data }">
                    <div>
                        <span v-if="data.data.townships.length > 0">
                            <template v-for="(township, index) in data.data.townships" :key="index">
                                <a class="hover-text" :href="`/townships/${township.id}`">{{ township.name }}</a>
                                <span v-if="index < data.data.townships.length - 1">, </span>
                            </template>
                        </span>
                        <span v-else> N/A </span>
                    </div>
                </template>
                <DxColumn 
                    data-field="address"
                    caption="ADDRESS"
                />
                <DxColumn 
                    data-field="phoneNumber"
                    caption="PHONE NUMBER"
                />
                <DxColumn 
                    data-field="totalRider"
                    caption="TOTAL RIDER"
                />
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
            :height="300"
            container=".dx-viewport"
            title="CREATE BRANCH"
            class="branch-create-popup">
            <!-- ... -->
                
                <div class="branch-create-form">
                    <div class="mb-1 flex justify-between">
                        <div>
                            <label for="branch_name" class="mb-1">Branch Name</label>
                            <DxTextBox 
                                v-model="branch.name" 
                                id="branch_name"
                                :isValid="isNameValid"
                                :onFocusOut="validateName"
                            />
                            <span v-if="!isNameValid" class="validation-error mt-1">Branch Name is required.</span>
                        </div>
                        <div>
                            <label for="phone_number" class="mb-1">Phone Number</label>
                            <DxTextBox
                                class="form-input"
                                id="phone_number"
                                v-model="branch.phone_number"
                                mode="tel"
                                :isValid="isPhoneNumberValid"
                                :onFocusOut="validatePhoneNumber"
                            />
                            <span v-if="!isPhoneNumberValid" class="validation-error mt-1">Phone Number is required.</span>
                        </div>
                    </div>
                    <div class="mb-1 flex justify-between">
                        <div>
                            <label for="city_name" class="mb-1">City</label>
                            <DxSelectBox
                                v-model="branch.city_id"
                                id="city_name"
                                :items="cityList"
                                displayExpr="name"
                                valueExpr="id"
                                :searchEnabled=true
                                placeholder="Select"
                                ref="city"
                                :isValid="isCityValid"
                                :onFocusOut="validateCity"
                            />
                            <span v-if="!isCityValid" class="validation-error mt-1">City is required.</span>
                        </div>
                        <div>
                            <label for="address" class="mb-1">Address</label>
                            <DxTextBox 
                                v-model="branch.address" 
                                id="address"
                                :isValid="isAddressValid"
                                :onFocusOut="validateAddress"
                            />
                            <span v-if="!isAddressValid" class="validation-error mt-1">Address is required.</span>
                        </div>
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
            searchButton: searchButton,
            gridData: [],
            pageSize: [10, 20, 50, 100],
            pageIndex: 0,
            createPopupVisible: false,
            branch: {
                name: null,
                phone_number: null,
                city_id: null,
                address: null,
            },
            isNameValid: true,
            isPhoneNumberValid: true,
            isCityValid: true,
            isAddressValid: true,
            isloading: false,
            isShowSuccess: false,
            townshipList: [],
            cityList: [],
            isToggleSearch: false,
        };
    },
    methods: {
        directToBranchDetail(e) {
            window.location.href = `/branches/${e.data.id}`;
        },
        clickCreateBtn() {
            this.createPopupVisible = true;
        },
        closeCreatePopUp() {
            this.createPopupVisible = false;
        },
        async getDataBySearch(event) {
            const input = event.target.value;
            const response = await fetch(`/api/branches?search=${input}`);
            this.fetchData(response);
        },
        async getDataByTownship(event) {
            const response = await fetch(`/api/branches?township_id=${event.value}`);
            this.fetchData(response);
        },
        async getDataByCity(event) {
            const response = await fetch(`/api/branches?city_id=${event.value}`);
            this.fetchData(response);
        },
        async getBranchTableData() {
            const response = await fetch(`/api/branches`);
            this.fetchData(response);
        },
        async fetchData(response) {
            try {
                const data = await response.json();
                
                const formattedData = data.data.map((branch, index) => ({
                    'id': branch.id,
                    'index': index + 1,
                    'branchName': branch.name,
                    'city': branch.city.name,
                    'address' : branch.address,
                    'phoneNumber' : branch.phone_number,
                    'totalRider' : branch.riders_count,
                    'townships' : branch.townships,
                    'cityId' : branch.city_id
                }));

                this.gridData = formattedData;
                console.log('grid table data => ', this.gridData);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        saveData() {
            console.log('save branch data');
            
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = this.branch;
            this.isloading = true;
            console.log(formData);

            fetch("/api/save-branch-data", {
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
                    this.branch.name = null;
                    this.branch.phone_number = null;
                    this.branch.city_id = null;
                    this.branch.address = null;
                    this.getBranchTableData();
                }
            })
            .catch((error) => {
                return error;
            });
        },
        validateName() {
            if(this.branch.name == null) {
                this.isNameValid = false;
            } else {
                this.isNameValid = true;
            }
        },
        validatePhoneNumber() {
            if(this.branch.phone_number == null) {
                this.isPhoneNumberValid = false;
            } else {
                this.isPhoneNumberValid = true;
            }
        },
        validateCity() {
            if(this.branch.city_id == null) {
                this.isCityValid = false;
            } else {
                this.isCityValid = true;
            }
        },
        validateAddress() {
            if(this.branch.address == null) {
                this.isAddressValid = false;
            } else {
                this.isAddressValid = true;
            }
        },
        async getTownshipList() {
            await fetch(`/api/get-township-list`)
                .then((res) => res.json())
                .then((data) => {
                    this.townshipList = data.data;
                });
        },
        async getCityList() {
            await fetch('/api/get-city-list')
                .then((res) => res.json())
                .then((data) => {
                    this.cityList = data.data;
                });
        },
        toggleSearch() {
            this.isToggleSearch = !this.isToggleSearch;
        }
    },
    mounted() {
        this.getTownshipList();
        this.getCityList();
        this.getBranchTableData();
        this.$refs.search.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.getDataBySearch
        );
    },
    computed: {
        containerHeight() {
            return this.isToggleSearch ? '67vh' : '78vh';
        }
    }
}
</script>
<style>
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-freespace-row {
    display: none;
}
.branch-create-form label {
    color: black;
    font-size: 12px;
    font-family: Lato;
    font-weight: 700;
}

.branch-create-form .dx-texteditor.dx-editor-outlined {
    width: 17vw;
    margin-top: 5px;
}
</style>
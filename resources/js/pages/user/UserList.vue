<template>
    <div class="px-9 py-2 font-lato user-list">
        <!-- page title -->
        <h1 class="page-title mb-7">USER</h1>
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
            <button class="bg-main text-white rounded-sm px-4 ms-3" @click="clickCreateBtn">Create New User</button>
        </div>
        <!-- table list view -->
        <div id="list-view-container">
            <!-- table -->
            <dx-data-grid
                :data-source="gridData"
                class="custom-data-grid"
                :columnAutoWidth="true"
                :remote-operations="false"
                ref="myDataGrid"
            >
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
                    show-scrollbar="never" /> 
            </dx-data-grid>
            <!-- paging -->
            
        </div>

        <DxPopup
            v-model:visible="createPopupVisible"
            :drag-enabled="false"
            :hide-on-outside-click="true"
            :show-title="true"
            :width="600"
            :height="500"
            container=".dx-viewport"
            title="CREATE USER"
            class="user-create-popup">
            <!-- ... -->
                
                <div class="user-create-form">
                    <div>
                        <p class="mb-1">USER NAME</p>
                        <DxTextBox 
                        v-model="user.name" 
                        :isValid="isNameValid"
                        :onFocusOut="validateName"/>
                        <span v-if="!isNameValid" class="validation-error mt-1">User Name is required.</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1">PHONE NUMBER</p>
                        <DxTextBox v-model="user.phone_number" 
                        :isValid="isPhoneNumberValid"
                        :onFocusOut="validatePhoneNumber"/>
                        <span v-if="!isPhoneNumberValid" class="validation-error mt-1">Phone Number is required.</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1">EMAIL</p>
                        <DxTextBox v-model="user.email" />
                    </div>
                    <div class="mt-3">
                        <p class="mb-1">PASSWORD</p>
                        <DxTextBox 
                        v-model="user.password" 
                        mode="password"
                        :isValid="isPasswordValid"
                        :onFocusOut="validatePassword"/>
                        <span v-if="!isPasswordValid" class="validation-error mt-1">Password is required.</span>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1">CONFIRM PASSWORD</p>
                        <DxTextBox 
                        v-model="user.confirm_password"
                        mode="password"
                        :isValid="isConfirmPasswordValid"
                        :onFocusOut="validateConfirmPassword"/>
                        <span v-if="!isConfirmPasswordValid && isPasswordMatch" class="validation-error mt-1">Confirm Password is required.</span>
                        <span v-if="!isConfirmPasswordValid && !isPasswordMatch" class="validation-error mt-1">Password and Confirm Password do not match.</span>
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
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxSearchPanel } from 'devextreme-vue/data-grid';
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
        DxLoadPanel
    },
    data() {
        return {
            searchButton: searchButton,
            gridData: [],
            pageSize: [10, 20, 50, 100],
            pageIndex: 0,
            createPopupVisible: false,
            user: {
                name: null,
                phone_number: null,
                email: null,
                password: null,
                confirm_password: null
            },
            isNameValid: true,
            isPhoneNumberValid: true,
            isPasswordValid: true,
            isConfirmPasswordValid: true,
            isPasswordMatch: true,
            isloading: false,
            isShowSuccess: false,
        };
    },
    methods: {
        clickCreateBtn() {
            this.createPopupVisible = true;
        },
        onShown() {
            // setTimeout(() => {
            //     this.loadingVisible = false;
            // }, 10000);
        },
        saveData() {
            console.log('save user data');
            if(this.user.password != this.user.confirm_password) {
                this.isPasswordMatch = false;
                this.isConfirmPasswordValid = false;
                return;
            } else {
                this.isPasswordMatch = true;
                this.isConfirmPasswordValid = true;
            }
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = this.user;
            this.isloading = true;
            console.log(formData);


            fetch("/api/save-user-data", {
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
                    this.getShopTableData();
                }
            })
            .catch((error) => {
                return error;
            });
        },
        closeCreatePopUp() {
            this.createPopupVisible = false;
        },
        async getDataBySearch(event) {
            const input = event.target.value;
            const response = await fetch(`/api/users?search=${input}`);
            this.fetchData(response);
        },
        async getShopTableData() {
            const response = await fetch(`/api/users`);
            this.fetchData(response);
        },
        async fetchData(response) {
            try {
                const data = await response.json();
                
                const formattedData = data.data.map((user) => ({
                    'ID': user.id,
                    'UserName': user.name,
                    'PhoneNumber': user.phone_number,
                    'Email': user.email ? user.email : '',
                }));

                this.gridData = formattedData;
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        
        validateName() {
            if(this.user.name == null) {
                this.isNameValid = false;
            } else {
                this.isNameValid = true;
            }
        },
        
        validatePhoneNumber() {
            if(this.user.phone_number == null) {
                this.isPhoneNumberValid = false;
            } else {
                this.isPhoneNumberValid = true;
            }
        },
        
        validatePassword() {
            if(this.user.password == null) {
                this.isPasswordValid = false;
            } else {
                this.isPasswordValid = true;
            }
        },
        
        validateConfirmPassword() {
            if(this.user.confirm_password == null) {
                this.isConfirmPasswordValid = false;
            } else {
                this.isConfirmPasswordValid = true;
            }
        }

        
    },
    mounted() {
        this.getShopTableData();
        this.$refs.search.$el.querySelector(".dx-texteditor-input").addEventListener(
            "input",
            this.getDataBySearch
        );
    },
}
</script>
 
<style>

.page-title {
    font-size: 34px !important;
    font-style: normal;
    font-weight: 800 !important;
    line-height: normal;
    text-transform: uppercase;
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

#filter-container {
    padding: 35px 0;
    border-top: 1px solid #AAAAAA;
}


#list-view-container {
    border-top: 1px solid #AAAAAA;
}
.custom-data-grid {
  overflow-x: auto;
}

.custom-data-grid .dx-datagrid-headers {
    border-bottom: none;
}

.custom-data-grid .dx-datagrid-headers .dx-datagrid-table .dx-header-row .dx-datagrid-action,
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td {
    text-align: center !important;
    border-left: none;
    border-right: none;
    border-bottom: 1px solid #D9D9D9;
    padding: 10px !important;
    text-transform: capitalize;
    font-family: Lato;
    color: black;
    font-size: 12px;
}
.custom-data-grid .dx-datagrid-headers .dx-datagrid-table .dx-header-row .dx-datagrid-action {
    font-weight: 700;
}
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td {
    font-weight: 400;
}

.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-freespace-row {
    display: block;
}

#pageSizeSelect {
    border: 1px solid #D9D9D9;
    border-radius: 7px;
    color: #D9D9D9;
    padding: 3px;
    font-size: 12px;
}

#pageSizeSelect:active, #pageSizeSelect:focus, #pageSizeSelect:focus-visible {
    border-color: #116A5B;
    color: #116A5B;
}

.user-list .dx-pager .dx-pages {
    display: block;
}

.dx-overlay-content .dx-popup-title {
    color: black;
    font-size: 24px;
    font-family: Lato;
    font-weight: 700;
}
.user-create-form p {
    color: black;
    font-size: 12px;
    font-family: Lato;
    font-weight: 700;
}
.validation-error {
    color: red;
    font-size: 10px;
    font-family: Lato;
}

.user-list .custom-data-grid {
    min-height: 80vh;
}

.loading-popup-content {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.spinner {
    border: 4px solid rgba(0, 0, 0, 0.3);
    border-top: 4px solid #116A5B;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    /* animation: spin 1s linear infinite, gradient 2s linear infinite; */
    margin-right: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    color: #116A5B;
    font-size: 13px;
    font-weight: 400;
    font-family: Lato;
}
</style>
 
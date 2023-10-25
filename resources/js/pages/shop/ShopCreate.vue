<template>
    <div>
        <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-md z-10">
                <!-- Card content goes here -->
                <div>
                    <div class="flex justify-between">
                        <h2 class="create-label">Create New Shop</h2>
                        <iconify-icon icon="gg:close" style="color: #aaa;" width="20" @click="closePopup"></iconify-icon>
                    </div>
                    <hr class="border-main my-7">
                    <div class="shop-create-form">
                        <div class="flex justify-between">
                            <div class="form-group">
                                <label for="online_shop_name">Online Shop Name</label>
                                <DxTextBox
                                    class="form-input mr-8"
                                    id="online_shop_name"
                                    v-model="online_shop_name"
                                />
                                <span v-show="validationErrors.name" class="validation-error mt-1">
                                    {{ validationErrors.name }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <DxTextBox
                                    class="form-input"
                                    id="phone_number"
                                    v-model="phone_number"
                                    mode="tel"
                                />
                                <span v-show="validationErrors.phoneNumber" class="validation-error mt-1">
                                    {{ validationErrors.phoneNumber }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="form-group">
                                <label for="city">City</label>
                                <DxSelectBox
                                    v-model="city"
                                    id="city"
                                    class="search-box form-input  mr-8"
                                    :items="cityList"
                                    displayExpr="name"
                                    valueExpr="id"
                                    :searchEnabled=true
                                    placeholder="Select"
                                    ref="city"
                                    :onValueChanged=cityValueChange
                                />
                                <span v-show="validationErrors.city" class="validation-error mt-1">
                                    {{ validationErrors.city }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="township">Township</label>
                                <DxSelectBox
                                    v-model="township"
                                    id="township"
                                    class="search-box form-input"
                                    :items="townshipList"
                                    displayExpr="name"
                                    valueExpr="id"
                                    :searchEnabled=true
                                    placeholder="Select"
                                    ref="township"
                                />
                                <span v-show="validationErrors.township" class="validation-error mt-1">
                                    {{ validationErrors.township }}
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <DxTextArea
                                id="address"
                                class="form-input mr-8"
                                v-model="address"
                                :min-height="70"
                                :auto-resize-enabled="true"
                            />
                            <span v-show="validationErrors.address" class="validation-error mt-1">
                                {{ validationErrors.address }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-3">
                    <!-- Close button -->
                    <button @click="closePopup" class="text-main border border-main w-70px h-8 rounded">
                        Cancel
                    </button>
                    <!-- Create button -->
                    <button @click="validateData" class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                        Save
                    </button>
                </div>
            </div>
        </div>
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
import DxTextBox from 'devextreme-vue/text-box';
import DxSelectBox from 'devextreme-vue/select-box';
import DxTextArea from 'devextreme-vue/text-area';

export default {
    components: {
        DxTextBox,
        DxSelectBox,
        DxTextArea
    },
    data() {
        return {
            online_shop_name: null,
            phone_number: null,
            city: null,
            township: null,
            address: null,
            cityList: [],
            townshipList: [],
            isLoading: false,
            isSuccess: false,
            validationErrors: {
                name: null,
                phoneNumber: null,
                city: null,
                township: null,
                address: null,
            },
        }
    },
    methods: {
        validateData() {
            let validationPassed = true;

            if(!this.online_shop_name) {
                this.validationErrors.name = "Online Shop Name is required.";
                validationPassed = false;
            }
            if(!this.phone_number) {
                this.validationErrors.phoneNumber = "Phone Number is required.";
                validationPassed = false;
            }
            if(!this.city) {
                this.validationErrors.city = "City is required.";
                validationPassed = false;
            }
            if(!this.township) {
                this.validationErrors.township = "Township is required.";
                validationPassed = false;
            }
            if(!this.address) {
                this.validationErrors.address = "Address is required.";
                validationPassed = false;
            }

            if(validationPassed) {
                this.createShop();
            }
        },
        async createShop() {
            this.isLoading = true;
            const apiUrl = '/api/shops';
            const requestData = {
                'name': this.online_shop_name,
                'township_id': this.township,
                'address': this.address,
                'phone_number': this.phone_number
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
                if(encodedData.status.toLowerCase() == 'success') {
                    this.isSuccess = true;
                    setTimeout(() => {
                        this.isSuccess = false;
                        this.closePopup();
                    }, 1000);
                }
            }, 1000);

            this.$emit('refreshData');
        },
        cityValueChange() {
            this.getTownshipList();
        },
        closePopup() {
            this.$emit('close');
        },
        async getCityList() {
            const response = await fetch('/api/get-city-list');
            const encodedData = await response.json();
            this.cityList = encodedData.data;
        },
        async getTownshipList() {
            const response = await fetch(`/api/get-township-list?city_id=${this.city}`);
            const encodedData = await response.json();
            this.townshipList = encodedData.data;
        },
    },
    mounted() {
        this.getCityList();
    }
};
</script>
<style scoped>
.form-input {
    border: 1px solid #D9D9D9 !important;
    border-radius: 2px;
    width: 309px;
    height: 32px;
    margin-top: 5px;
}

.form-input :last-of-type {
    margin-bottom: 0;
}

.create-label {
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.shop-create-form label {
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: 18px;
    text-transform: capitalize;
    color: #000000;
}

</style>
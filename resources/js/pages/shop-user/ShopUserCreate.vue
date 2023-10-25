<template>
    <div @click="closePopupOnClickOutside">
        <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div id="shopUserCreateCard" class="bg-white p-8 rounded-lg shadow-md z-10">
                <!-- Card content goes here -->
                <div>
                    <div class="flex justify-between items-center">
                        <h2 class="card-title">CREATE SHOP USER</h2>
                        <iconify-icon icon="gg:close" style="color: #aaa;" width="20" @click="closePopup"></iconify-icon>
                    </div>
                    <hr class="border-main">
                    <div class="shop-user-create-form">
                        <div class="form-group">
                            <label for="name">NAME</label>
                            <DxTextBox
                                class="form-input mr-8"
                                id="name"
                                v-model="name"
                            />
                            <span v-show="validationErrors.name" class="validation-error mt-1">
                                {{ validationErrors.name }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">PHONE NUMBER</label>
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
                        <div class="form-group">
                            <label for="shop">SHOP</label>
                            <DxSelectBox
                                v-model="shop"
                                id="shop"
                                class="search-box form-input  mr-8"
                                :items="shopList"
                                displayExpr="name"
                                valueExpr="id"
                                placeholder=""
                                :searchEnabled=true
                            />
                            <span v-show="validationErrors.shop" class="validation-error mt-1">
                                {{ validationErrors.shop }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="email">EMAIL</label>
                            <DxTextBox
                                class="form-input mr-8"
                                id="email"
                                v-model="email"
                            />
                        </div>
                        <div class="form-group">
                            <label for="password">PASSWORD</label>
                            <DxTextBox
                                class="form-input mr-8"
                                id="password"
                                v-model="password"
                                mode="password"
                            />
                            <span v-show="validationErrors.password" class="validation-error mt-1">
                                {{ validationErrors.password }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">CONFIRM PASSWORD</label>
                            <DxTextBox
                                class="form-input mr-8"
                                id="confirmPassword"
                                v-model="confirmPassword"
                                mode="password"
                            />
                            <span v-show="validationErrors.confirmPassword" class="validation-error mt-1">
                                {{ validationErrors.confirmPassword }}
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
            name: null,
            phone_number: null,
            shop: null,
            email: null,
            password: null,
            confirmPassword: null,
            shopList: [],
            isLoading: false,
            isSuccess: false,
            validationErrors: {
                name: null,
                phoneNumber: null,
                shop: null,
                password: null,
                confirmPassword: null,
            },
        }
    },
    methods: {
        closePopupOnClickOutside(event) {
            // Check if the click event occurred outside of the popup
            if (!this.$el.querySelector("#shopUserCreateCard").contains(event.target)) {
            // Close the popup by calling your closePopup method
            this.closePopup();
            }
        },
        validateData() {
            let validationPassed = true;
            if(!this.name) {
                this.validationErrors.name = "Name is required.";
                validationPassed = false;
            }
            if(!this.phone_number) {
                this.validationErrors.phoneNumber = "Phone Number is required.";
                validationPassed = false;
            }
            if (!this.shop) {
                this.validationErrors.shop = "Shop is required.";
                validationPassed = false;
            }
            if(!this.password) {
                this.validationErrors.password = 'Please enter your password';
                validationPassed = false;
            }
            if (this.password !== this.confirmPassword) {
                this.validationErrors.password = "Passwords don't match.";
                this.validationErrors.confirmPassword = "Passwords don't match.";
                validationPassed = false;
            }

            if (validationPassed) {
                this.createShopUser();
            }
        },
        async createShopUser() {
            this.isLoading = true;
            const apiUrl = '/api/shop-users';
            const requestData = {
                'name': this.name,
                'phone_number': this.phone_number,
                'shop_id': this.shop,
                'email': this.email,
                'password': this.password
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
        closePopup() {
            this.$emit('close');
        },
        async getShopList() {
            const response = await fetch('/api/get-shop-list');
            const encodedData = await response.json();
            this.shopList = encodedData.data;
        },
    },
    mounted() {
        if(this.shopList.length == 0) {
            this.getShopList();
        }
    }
};
</script>
<style scoped>
#shopUserCreateCard {
    width: 710px;
    height: 628px;
    overflow: auto;
}

#shopUserCreateCard::-webkit-scrollbar {
    display: none;
}

#shopUserCreateCard hr {
    margin-top: 10px;
    margin-bottom: 20px;
}

.form-input {
    border: 1px solid #D9D9D9 !important;
    border-radius: 2px;
    width: 100%;
    height: 32px;
    margin-top: 5px;
}

.form-group {
    margin-bottom: 20px;
}

.form-input :last-of-type {
    margin-bottom: 0;
}

.shop-user-create-form label {
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 18px;
    color: #000000;
}

</style>
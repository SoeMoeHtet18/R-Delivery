<template>
    <div @click="closePopupOnClickOutside">
        <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
        <div class="fixed inset-0 flex items-center justify-end z-50">
            <div id="shopUserDetailCard" class="bg-white p-8 rounded-lg shadow-md z-10">
                <!-- Card content goes here -->
                <div>
                    <div class="flex justify-between">
                        <h2 class="card-title">{{shopUser.userName}}</h2>
                        <iconify-icon icon="gg:close" style="color: #aaa;" width="20" @click="closePopup"></iconify-icon>
                    </div>
                    <hr class="border-main">
                    <div class="detail-container">
                        <div class="flex justify-end" v-if="!isEdit">
                            <!-- Edit button -->
                            <button @click.stop="editShopUser" class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                                Edit
                            </button>
                        </div>
                        <div class="flex justify-end" v-if="isEdit">
                            <!-- Close button -->
                            <button @click.stop="cancelEdit" class="text-main border border-main w-70px h-8 rounded">
                                Cancel
                            </button>
                            <!-- Create button -->
                            <button @click.stop="validateShopuser" class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                                Save
                            </button>
                        </div>
                        <div class="shop-user-info-container" v-if="!isEdit">
                            <div class="mb-5">
                                <h5>NAME</h5>
                                <div class="info-box">
                                    {{ shopUser.userName }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>PHONE NUMBER</h5>
                                <div class="info-box">
                                    {{ shopUser.phoneNumber }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>SHOP</h5>
                                <div class="info-box">
                                    {{ shopUser.shopName }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>EMAIL</h5>
                                <div class="info-box">
                                    {{ shopUser.email }}
                                </div>
                            </div>
                        </div>
                        <div class="shop-user-info-edit-container" v-if="isEdit">
                            <div class="form-group">
                                <label for="name">NAME</label>
                                <DxTextBox
                                    class="form-input mr-8"
                                    id="name"
                                    v-model="shopUser.userName"
                                    ref="name"
                                />
                                <span v-show="validationErrors.userName" class="validation-error mt-1">
                                    {{ validationErrors.userName }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">PHONE NUMBER</label>
                                <DxTextBox
                                    class="form-input"
                                    id="phone_number"
                                    v-model="shopUser.phoneNumber"
                                    mode="tel"
                                />
                                <span v-show="validationErrors.phoneNumber" class="validation-error mt-1">
                                    {{ validationErrors.phoneNumber }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="shop">SHOP</label>
                                <DxSelectBox
                                    v-model="shopUser.shopId"
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
                                    v-model="shopUser.email"
                                />
                            </div>
                            <div class="flex justify-end" v-if="isEdit">
                                <!-- Edit button -->
                                <button @click.stop="showEditPassword" class="text-white bg-main border border-main px-5 h-8 rounded ml-3">
                                    Update Password
                                </button>
                            </div>
                            <div v-show="isUpdatePassword">
                                <div class="form-group">
                                    <label for="password">ENTER OLD PASSWORD</label>
                                    <DxTextBox
                                        class="form-input mr-8"
                                        id="password"
                                        v-model="oldPassword"
                                        mode="password"
                                    />
                                    <span v-show="validationErrors.oldPassword" class="validation-error mt-1">
                                        {{ validationErrors.oldPassword }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">ENTER NEW PASSWORD</label>
                                    <DxTextBox
                                        class="form-input mr-8"
                                        id="confirmPassword"
                                        v-model="newPassword"
                                        mode="password"
                                    />
                                    <span v-show="validationErrors.newPassword" class="validation-error mt-1">
                                        {{ validationErrors.newPassword }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">ENTER NEW PASSWORD AGAIN</label>
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
                    </div>
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
    props: ['shopUser'],
    data() {
        return {
            originData: null,
            isEdit: false,
            isUpdatePassword: false,
            shopList: [],
            oldPassword: null,
            newPassword: null,
            confirmPassword: null,
            validationErrors: {
                userName: null,
                phoneNumber: null,
                shop: null,
                oldPassword: null,
                newPassword: null,
                confirmPassword: null,
            },
            isLoading: false,
            isSuccess: false,
        }
    },
    methods: {
        restoreOriginData() {
            this.shopUser.userName = this.originData.userName;
            this.shopUser.phoneNumber = this.originData.phoneNumber;
            this.shopUser.email = this.originData.email;
            this.shopUser.shopId = this.originData.shopId;
            this.shopUser.shopName = this.originData.shopName;
        },
        showEditPassword() {
            this.isUpdatePassword = true;
        },
        cancelEdit() {
            this.isEdit = false;
            this.restoreOriginData();
        },
        editShopUser() {
            this.isEdit = true;
            if(!this.originData) {
                this.originData = {...this.shopUser};
            }
            if(this.shopList.length == 0) {
                this.getShopList();
            }
        },
        closePopupOnClickOutside(event) {
            // Check if the click event occurred outside of the popup
            if (!this.$el.querySelector("#shopUserDetailCard").contains(event.target)) {
                // Close the popup if user clicked outside
                if(this.isEdit) {
                    this.restoreOriginData();
                }
                this.closePopup();
            }
        },
        async validateShopuser() {
            let validationPassed = true;

            if (this.isUpdatePassword) {
                if(!this.oldPassword) {
                    this.validationErrors.oldPassword = 'Please enter your old password';
                    validationPassed = false;
                }
                if(!this.newPassword) {
                    this.validationErrors.newPassword = 'Please enter your new password';
                    validationPassed = false;
                }
                if(!this.oldPassword) {
                    this.validationErrors.confirmPassword = 'Please enter your new password again';
                    validationPassed = false;
                }
                if(this.oldPassword) {
                    const apiUrl = `/api/shop-users/${this.shopUser.id}/check-password`;
                    const requestData = {
                        'password': this.oldPassword
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

                    if (encodedData.status.toLowerCase() == 'fail') {
                        this.validationErrors.oldPassword = 'Your password is incorrect.';
                        validationPassed = false;
                    } else if (this.newPassword !== this.confirmPassword) {
                        this.validationErrors.newPassword = "Passwords don't match.";
                        this.validationErrors.confirmPassword = "Passwords don't match.";
                        validationPassed = false;
                    }
                }
                
            }

            if (!this.shopUser.userName) {
                this.validationErrors.userName = "Name is required.";
                validationPassed = false;
            }

            if (!this.shopUser.phoneNumber) {
                this.validationErrors.phoneNumber = "Phone Number is required.";
                validationPassed = false;
            }

            if (!this.shopUser.shopId) {
                this.validationErrors.shop = "Shop is required.";
                validationPassed = false;
            }

            if (validationPassed) {
                this.updateShopUser();
            }
        },
        async updateShopUser() {
            this.isLoading = true;
            const apiUrl = `/api/shop-users/${this.shopUser.id}`;
            console.log(apiUrl);
            const requestData = {
                'name': this.shopUser.userName,
                'phone_number': this.shopUser.phoneNumber,
                'shop_id': this.shopUser.shopId,
                'email': this.shopUser.email,
            };

            if (this.isUpdatePassword) {
                requestData.password = this.newPassword;
            }

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
                if (encodedData.status.toLowerCase() === 'success') {
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
            if(this.isEdit) {
                this.restoreOriginData();
            }
            this.$emit('close');
        },
        async getShopList() {
            const response = await fetch('/api/get-shop-list');
            const encodedData = await response.json();
            this.shopList = encodedData.data;
        },
    },
};
</script>
<style scoped>
#shopUserDetailCard {
    width: 45vw;
    height: 100vh;
    overflow: auto;
}

#shopUserDetailCard::-webkit-scrollbar {
    display: none;
}

#shopUserDetailCard hr {
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

.info-box {
    width: 100%;
    background-color: #F1F5F5;
    border: 1px solid #D9D9D9;
    padding: 7px 10px;
    font-size: 12px !important;
    font-style: normal;
    font-weight: 400;
    line-height: 18px;
}

.shop-user-info-container h5, .shop-user-info-edit-container label {
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 18px;
    color: #000000;
}

</style>
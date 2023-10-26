<template>
    <div @click="closePopupOnClickOutside">
        <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div id="itemTypeCreateCard" class="bg-white p-8 shadow-md z-10 w-1/2">
                <!-- Card content goes here -->
                <div>
                    <div class="flex justify-between items-center">
                        <h2 class="card-title">CREATE ITEM TYPE</h2>
                        <iconify-icon icon="gg:close" style="color: #aaa;" width="20" @click="closePopup"></iconify-icon>
                    </div>
                    <hr class="border-main">
                    <div class="item-type-create-form">
                        <div class="form-group">
                            <label for="name">ITEM TYPE NAME</label>
                            <DxTextBox
                                class="form-input mr-8"
                                id="name"
                                v-model="name"
                            />
                            <span v-show="validationErrors.name" class="validation-error mt-1">
                                {{ validationErrors.name }}
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
            isLoading: false,
            isSuccess: false,
            validationErrors: {
                name: null,
            },
        }
    },
    methods: {
        closePopupOnClickOutside(event) {
            // Check if the click event occurred outside of the popup
            if (!this.$el.querySelector("#itemTypeCreateCard").contains(event.target)) {
            // Close the popup by calling your closePopup method
            this.closePopup();
            }
        },
        validateData() {
            let validationPassed = true;
            if(!this.name) {
                this.validationErrors.name = "Item Type Name is required.";
                validationPassed = false;
            }

            if (validationPassed) {
                this.createItemType();
            }
        },
        async createItemType() {
            this.isLoading = true;
            const requestData = {
                'name': this.name,
            };

            const csrfToken = document.head.querySelector('meta[name="_token"]').content;

            const response = await fetch('/api/item-types', {
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
    }
};
</script>
<style scoped>
#itemTypeCreateCard {
    border-radius: 20px;
    box-shadow: 0px 15px 10px 15px rgba(0, 0, 0, 0.50);
}

#itemTypeCreateCard hr {
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

.item-type-create-form label {
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 18px;
    color: #000000;
}

</style>
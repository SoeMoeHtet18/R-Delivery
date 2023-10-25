<template>
    <div class="px-9 py-2 font-lato branch-detail">
        <div class="flex justify-between items-end mb-7">
            <h1 class="page-title">{{ BranchInfo.name }}</h1>
            <!-- <iconify-icon icon="ri:close-line" style="color: #aaa;" width="20" @click="directToShopList"></iconify-icon> -->
        </div>
        <!-- branch detail content -->
        <div id="branch-info-container" class="border-t border-grey mt-5 pt-5">
            <div class="flex justify-end">
                <!-- edit button -->
                <button v-if="!isEdit" @click="editBranch"
                class="bg-main text-white w-70px h-8">Edit</button>
                <div>
                    <!-- cancel button -->
                    <button v-if="isEdit" @click="cancelEdit"
                        class="bg-white text-main w-70px h-8 border border-main">Cancel</button>
                    <!-- update/save button -->
                    <button v-if="isEdit" @click="updateBranch"
                        class="bg-main text-white w-70px h-8 ml-2.5">Save</button>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 pb-7.5">
                <div class="flex flex-col">
                    <h5 class="info-label">Branch Name</h5>
                    <div v-if="!isEdit" 
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ BranchInfo.name }}
                    </div>
                    <DxTextBox v-if="isEdit"
                        v-model="BranchInfo.name"
                        height="37"
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Phone Number</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ BranchInfo.phone_number }}
                    </div>
                    <DxTextBox v-if="isEdit"
                        v-model="BranchInfo.phone_number"
                        mode="tel"
                        height="37"
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">City</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ BranchInfo.city?.name }}
                    </div>
                    <DxSelectBox v-if="isEdit"
                        v-model="BranchInfo.city_id"
                        :items="cityList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="City"
                        ref="city"
                        height="37"
                        :onValueChanged=cityValueChange
                    />
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Township</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        <!-- {{ BranchInfo.township?.name }} -->
                        n/a
                    </div>
                    <!-- <DxSelectBox v-if="isEdit"
                        v-model="BranchInfo.township.id"
                        :items="townshipList"
                        displayExpr="name"
                        valueExpr="id"
                        :searchEnabled=true
                        placeholder="Township"
                        ref="township"
                        height="37"
                    /> -->
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Joined Date</h5>
                    <div
                        id="joinedDate" class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ joinedDate }}
                    </div>
                </div>
                <div class="flex flex-col">
                    <h5 class="info-label">Address</h5>
                    <div v-if="!isEdit"
                        class="bg-info-main border border-info-secondary rounded-sm text-grey info-box">
                        {{ BranchInfo.address }}
                    </div>
                    <DxTextArea v-if="isEdit"
                        v-model="BranchInfo.address"
                        :auto-resize-enabled="true"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { DxTextBox, DxButton as DxTextBoxButton } from 'devextreme-vue/text-box';
import { DxTabs, DxItem } from 'devextreme-vue/tabs';
import DxSelectBox from 'devextreme-vue/select-box';
import DxTextArea from 'devextreme-vue/text-area';
import { DxDataGrid, DxPager, DxPaging, DxScrolling, DxColumn } from 'devextreme-vue/data-grid';
export default {
    props: ['id'],
    components: {
        DxTextBox,
        DxTextBoxButton,
        DxTabs,
        DxItem,
        DxDataGrid,
        DxPager,
        DxPaging,
        DxScrolling,
        DxColumn,
        DxSelectBox,
        DxTextArea
    },
    data() {
        return {
            pageSize: [10, 20, 50, 100],
            BranchInfo: {},
            selectedIndex: 0,
            isEdit: false,
            isLoading: false,
            isSuccess: false,
            townshipList: [],
            cityList: [],
        }
    },
    methods: {
        cancelEdit() {
            this.isEdit = false;
        },
        editBranch() {
            this.isEdit = true;
            this.getCityList();
            this.getTownshipList();
        },
        updateBranch() {
            console.log('update branch data');
        },
        async getBranchDetail() {
            const response = await fetch(`/api/branches/${this.id}`);
            const data = await response.json();
            this.BranchInfo = data.data;
        },
        formatDateWithLongText(dateString) {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            return `${day} ${month}, ${year}`;
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
    },
    mounted() {
        this.getShopDetail();
    },
    computed: {
        joinedDate() {
            return this.formatDateWithLongText(this.BranchInfo.created_at);
        },
    },
}
</script>
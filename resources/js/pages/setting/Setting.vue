<template>
    <div class="font-lato">
        <div>
            <h1 class="font-lato main-title">SETTING</h1>
            <hr class="long-line">
            <h2 class="font-lato sub-title">General</h2>
            <hr class="short-line">
        </div>
        <div class="flex">
            <div class="collection w-1/2">
                <div class="rectangle">
                    <span class="label">Collection Method</span>
                    <h5 class="lable-title">Always On</h5>
                    <DxRadioGroup :items="collectionMethods" layout="vertical" v-model="selectedCollectionMethod"
                        :valueChanged="updateSetting" />
                </div>
            </div>
            <div class="schedule-date w-1/2">
                <div class="rectangle">
                    <span class="label">Schedule Date</span>
                    <h5 class="lable-title">Always On</h5>
                    <DxRadioGroup :items="scheduleDate" layout="vertical" v-model="selectedScheduleDate"
                        :valueChanged="updateSetting" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import DxRadioGroup from 'devextreme-vue/radio-group';

export default {
    components: {
        DxRadioGroup
    },
    data() {
        return {
            selectedCollectionMethod: null,
            selectedScheduleDate: null,
            collectionMethods: [
                { text: 'Pick Up', value: 'pick_up' },
                { text: 'Drop Off', value: 'drop_off' },
            ],
            scheduleDate: [
                { text: 'Today', value: 'today' },
                { text: 'Tomorrow', value: 'tomorrow' },
            ],
        }
    },
    methods: {
        updateSetting() {
            const csrf = document.querySelector('meta[name="_token"]').content;
            let formData = {
                'collection_method': this.selectedCollectionMethod,
                'schedule_date': this.selectedScheduleDate
            };

            fetch(`/api/setting/update`, {
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
                    console.log(data);
                })
                .catch((error) => {
                    return error;
                });
        },
    }
}
</script>
<style scoped>
.main-title {
    font-size: 34px;
    font-style: normal;
    font-weight: 800;
    line-height: normal;
    text-transform: uppercase;
}

.long-line {
    height: 2px;
    background-color: #116A5B;
    border: none;
    margin: 20px 0;
}

.sub-title {
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.short-line {
    width: 50px;
    height: 3px;
    background-color: #116A5B;
    border: none;
    margin: 10px 0 30px;
}

.collection {
    box-sizing: border-box;
    inset: 0;
}

.rectangle {
    width: 100%;
    padding: 30px;
    position: relative;
    border: 1px solid #116a5b;
    border-radius: 10px !important;
}

.label {
    position: absolute;
    top: -15px;
    left: 125px;
    transform: translateX(-50%);
    background-color: #fff;
    padding: 0 10px;
    color: #000000;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.label-title {
    color: #505050;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 17px;
    margin-bottom: 20px;
}

</style>

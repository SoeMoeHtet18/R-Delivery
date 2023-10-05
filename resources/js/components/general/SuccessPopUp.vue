<template>
    <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-md z-10">
            <div class="flex items-center">
                <iconify-icon icon="gg:check-o" style="color: #2ab936;" width="24"></iconify-icon>
                <p class="success-label">Done Successfully.</p>
            </div>
        </div>
    </div>
</template>
<style>
.success-label {
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: 17px;
    color: #2AB936;
    margin-left: 15px;
}
</style>
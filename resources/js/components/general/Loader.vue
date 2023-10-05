<!-- Loader.vue -->
<template>
    <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-md z-10">
            <div class="loader">
                <div class="spinner"></div>
                <p class="loading-label">Loading, please wait...</p>
            </div>
        </div>
    </div>
</template>
  
<style scoped>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.spinner {
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 1px solid #116A5B;
    width: 25px;
    height: 25px;
    animation: spin 2s linear infinite;
    margin-right: 15px;
}

.loading-label {
    color: #116A5B;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
  
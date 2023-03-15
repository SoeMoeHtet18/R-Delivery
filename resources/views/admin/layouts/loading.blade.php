<style>
    .overlay {
        height: 100vh;
        width: 100vw;
        position: fixed;
        top: 0;
        bottom: 0;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, .7);
        font-size: 3rem;
    }
    .overlay > i {
        font-size: 2.5rem;
        color: white;
    }
</style>

<div id="loading" class="overlay" style="display: none">
    <i class="fa fa-refresh fa-spin"></i>
</div>
<style>
        .modern-flash-message {
        position: fixed;
        top: 25px;
        right: 25px;
        z-index: 9999;

        min-width: 320px;
        max-width: 450px;

        display: flex;
        align-items: center;
        gap: 14px;

        padding: 15px 18px;

        background: #ffffff;
        border-radius: 12px;

        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);

        border-left: 4px solid #28a745;

        animation: slideIn 0.35s ease-out;
    }

    .flash-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #e9f8ef;
        color: #28a745;

        flex-shrink: 0;
    }

    .flash-content {
        display: flex;
        flex-direction: column;
        gap: 2px;

        flex: 1;
    }

    .flash-content strong {
        font-size: 14px;
        color: #212529;
    }

    .flash-content span {
        font-size: 13px;
        color: #6c757d;
    }

    .flash-close {
        border: none;
        background: transparent;

        font-size: 22px;
        line-height: 1;

        color: #adb5bd;
        cursor: pointer;

        padding: 0 3px;
    }

    .flash-close:hover {
        color: #343a40;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(40px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(40px);
        }
    }
</style>
<div id="flash-message" style="display: none" class="modern-flash-message success">
    <div class="flash-icon"><i class="fas fa-check"></i></div>
    <div class="flash-content"><strong><span id="flash-content-message"></span></strong></div>
    <button type="button" class="flash-close" onclick="closeFlashMessage()">&times;</button>
</div>

<script>
    $(document).ready( function () {
        setTimeout(function () {
                closeFlashMessage();
            }, 4000);
    })
    function closeFlashMessage() {
        const message = document.getElementById('flash-message');

        if (message) {
            message.style.animation = 'slideOut 0.3s ease-in forwards';

            setTimeout(function () {
                message.remove();
            }, 300);
        }
    }
</script>

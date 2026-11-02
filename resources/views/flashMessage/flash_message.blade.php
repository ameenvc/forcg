<style>
    .modern-flash-message {
        position: fixed;
        top: 50px;
        z-index: 9999;

        width: 100%;
        display: flex;
        align-items: center;
    }

</style>
<div id="flash-message" class="alert alert-dismissible" style="display:none; font-size:14px; position: relative; width: 100%">
    <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
    <span id="flash-content-message"></span>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready( function () {
        let messageConfig = JSON.parse(sessionStorage.getItem('message_config'));
        if (!messageConfig.dont_fade) {
            setTimeout(function () {
                $('#flash-message').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 3000); // 5000 ms = 5 seconds
        }
    });

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

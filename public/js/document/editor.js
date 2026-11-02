/**
 * Initialize CKEditor with custom toolbar and file upload options.
 *
 * This function sets up the CKEditor instance with a specified container ID,
 * configures the toolbar, and sets up the CKFinder for file uploads.
 *
 * @param {string} containerId - The ID of the container where the CKEditor will be initialized.
 */
function createCKEditor(containerId) {
    ClassicEditor
        .create(document.querySelector(containerId), {
            toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'ckfinder',
                'mediaEmbed', 'blockQuote', 'insertTable', 'undo', 'redo'
            ],
            ckfinder: {
                uploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
            }
        });
}

/**
 * Initialize Summernote editor with YouTube URL conversion.
 *
 * This function sets up the Summernote editor with custom options and handles YouTube URL conversion to embed format.
 * It also manages the display of original URLs and alerts within the editor's modal dialog.
 *
 * @param {string} containerId - The ID of the container where the Summernote editor will be initialized.
 * @param {object} options - Additional options for the Summernote editor initialization.
 */
function createSummernoteEditor(containerId, options = {}) {
    $(document).ready(function () {
        /**
         * -----------------------------------------------------------------------
         * Initialize Summernote editor
         * ----------------------------------------------------------------------
         */
        // Default options for Summernote editor
        const defaultOptions = {
            height: 200,
            dialogsInBody: true,
        }
        $(containerId).summernote(options ? Object.assign(defaultOptions, options) : defaultOptions);

        /**
         * -----------------------------------------------------------------------
         *  Handel YouTube URL conversion: Convert YouTube URLs to embed format
         *    For example:
         *     - https://www.youtube.com/watch?v=VIDEO_ID
         *     - https://youtu.be/VIDEO_ID
         *     - https://www.youtube.com/live/VIDEO_ID
         *     - https://www.youtube.com/shorts/VIDEO_ID
         *     - Converts to:
         *         - https://www.youtube.com/embed/VIDEO_ID
         * ----------------------------------------------------------------------
         */
        let isConverting = false; // Flag to prevent conversion loops

        $(document).on('input', '.note-video-url', function() {
            if (isConverting) return; // Skip if we're in the middle of conversion

            const $input = $(this);
            const url = $input.val().trim();
            const $formGroup = $input.closest('.form-group');

            // Check if this input already contains an embed URL
            if (url.includes('youtube.com/embed/')) {
                return;
            }

            if (url && isYouTubeUrl(url)) {
                isConverting = true; // Set flag to prevent loops

                const embedUrl = convertYouTubeUrl(url);
                const originalUrl = url;

                // Update input with embed URL
                $input.val(embedUrl);

                // Remove any existing alert and original URL display
                $formGroup.find('.url-converted-alert').remove();
                $formGroup.find('.original-url-display').remove();

                // Add alert message above input
                const alertHtml = '<div class="alert alert-info alert-sm url-converted-alert" style="margin-bottom: 10px; padding: 8px 12px; font-size: 12px;">' +
                    '<i class="fa fa-info-circle"></i> URL has been converted to embed format' +
                    '</div>';
                $input.before(alertHtml);

                // Add original URL displayed below input
                const originalUrlHtml = '<div class="original-url-display" style="margin-top: 8px; padding: 8px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">' +
                    '<small class="text-muted"><strong>Original URL:</strong></small><br>' +
                    '<small style="word-break: break-all; color: #6c757d;">' + originalUrl + '</small>' +
                    '</div>';
                $input.after(originalUrlHtml);

                // Reset flag after a short delay
                setTimeout(function() {
                    isConverting = false;
                }, 100);
            } else {
                // If not a valid YouTube URL, remove any existing alert and original URL display
                $formGroup.find('.url-converted-alert').remove();
                $formGroup.find('.original-url-display').remove();
            }
        });

        // Clean up when modal is closed
        $(document).on('hidden.bs.modal', '.modal', function () {
            $(this).find('.url-converted-alert').remove();
            $(this).find('.original-url-display').remove();
            $(this).find('.note-video-url').val(''); // Clear input
        });

        function isYouTubeUrl(url) {
            return /youtube\.com\/(?:watch\?v=|live\/|shorts\/)|youtu\.be\//.test(url);
        }

        function convertYouTubeUrl(url) {
            let videoId = '';

            if (url.includes('youtube.com/watch?v=')) {
                videoId = url.split('v=')[1].split('&')[0];
            } else if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            } else if (url.includes('youtube.com/live/')) {
                videoId = url.split('live/')[1].split('?')[0];
            } else if (url.includes('youtube.com/shorts/')) {
                videoId = url.split('shorts/')[1].split('?')[0];
            }

            return 'https://www.youtube.com/embed/' + videoId;
        }

        /**
         * -----------------------------------------------------------------------
         *  Handel Issue: Using summernote inside Nested Bootstrap modal dialogs
         *  and solving the problem of tooltips and popovers
         *  https://github.com/summernote/summernote/issues/2644
         *
         * ----------------------------------------------------------------------
         */
        $(document).on("show.bs.modal", '.modal', function () {
            const zIndex = 100000 + (10 * $(".modal:visible").length);
            $(this).css("z-index", zIndex);
            setTimeout(function () {
                $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
            }, 0);
        }).on("hidden.bs.modal", '.modal', function () {
            $(".modal:visible").length && $("body").addClass("modal-open");
        });
        $(document).on('inserted.bs.tooltip', function (event) {
            const zIndex = 100000 + (10 * $(".modal:visible").length);
            const tooltipId = $(event.target).attr("aria-describedby");
            $("#" + tooltipId).css("z-index", zIndex);
        });
        $(document).on('inserted.bs.popover', function (event) {
            const zIndex = 100000 + (10 * $(".modal:visible").length);
            const popoverId = $(event.target).attr("aria-describedby");
            $("#" + popoverId).css("z-index", zIndex);
        });
    });
}

/**
 * Initialize the editor based on the specified driver.
 *
 * @param {string} containerId - The ID of the container where the editor will be initialized.
 * @param {object} options - Additional options for the editor initialization.
 * @param {string} options.driver - The editor driver to use ('ckeditor' or 'summernote').
 */
function editorInit(containerId, options = {}) {
    const driver = options.driver || 'summernote'; // Default to Summernote if no driver is specified

    if (driver === 'ckeditor') {
        createCKEditor(containerId);
    } else if (driver === 'summernote') {
        createSummernoteEditor(containerId);
    } else {
        console.error('Unsupported editor driver: ' + driver);
    }
}
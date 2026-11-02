function initTextField(textFieldId, required) {
    $(textFieldId).summernote({
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['font', ['clear']],
            ['view', ['fullscreen', 'codeview']]
        ],
        required: required,
        callbacks: {
            onChange: function(contents, $editable) {
                $(`${textFieldId}-error`).hide();
            },
            onPaste: function(e) {
                e.preventDefault();

                let html = (e.originalEvent || e).clipboardData.getData('text/html');

                let text = (e.originalEvent || e).clipboardData.getData('text/plain');

                if (!html) {
                    document.execCommand('insertText', false, text);
                    return;
                }

                let div = document.createElement('div');
                div.innerHTML = html;

                // Remove style, class, id from all elements
                $(div).find('*').each(function() {
                    $(this).removeAttr('style class id');
                    // $(this).css('font-size', '13px');
                });

                if ($(div).find('script, iframe, object, embed, img')) {
                    alert('Some unsupported content will be removed. Please review the changes before saving.');
                }
                // Remove unwanted tags
                $(div).find('script, iframe, object, embed, img').remove();

                // Insert cleaned HTML
                $(textFieldId).summernote('pasteHTML', div.innerHTML);
                // document.execCommand('insertText', false, text);

                // if (!html) {
                //     document.execCommand('insertText', false, text);
                //     return;
                // }

                // let div = document.createElement('div');
                // div.innerHTML = html;

                // // Remove style/class attributes
                // $(div).find('*').removeAttr('style class id');

                // // Remove dangerous tags
                // $(div).find('script, iframe, object').remove();

                // $(textFieldId).summernote('pasteHTML', div.innerHTML);
            }
        }
    });
    $('.note-editable').css('font-size', '13px');
}

function disableTextField(textFieldId) {
    $(textFieldId).summernote({
        toolbar: false
    });
    $('.note-editable').css('font-size', '13px');
    $(textFieldId).summernote('disable');
}

function validateSummerNoteTextField(textFieldId) {
    let editor = $(textFieldId);
    let content = editor.summernote('code');
    // Remove HTML tags and spaces
    let plainText = $('<div>').html(content).text().trim();

    if (editor.summernote('isEmpty') || plainText == '') {
        $(`${textFieldId}-error`).show();
        return false;
    } else {
        $(`${textFieldId}-error`).hide();
        return true;
    }
}

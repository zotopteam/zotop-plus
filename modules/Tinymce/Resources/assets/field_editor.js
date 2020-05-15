/* zotop field editor */

function field_editor(selector, options)
{
    // 自定义上传
    options.images_upload_handler = function (blobInfo, success, failure) {
        var xhr, formData;

        xhr = new XMLHttpRequest();
        xhr.withCredentials = options.images_upload_credentials;
        xhr.open('POST', options.images_upload_url);

        xhr.onload = function() {
            var json;

            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status + ' '.xhr.responseText);
                return;
            }

            json = JSON.parse(xhr.responseText);

            if (!json) {
                failure(xhr.responseText);
                return;
            }

            if (!json.state) {
                failure(json.content);
                return;                
            }

            success(json.url);
        };

        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
    };
    
    $(selector).tinymce(options);
}

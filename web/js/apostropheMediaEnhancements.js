if (!window.apostrophe)
{
    window.apostrophe = new aConstructor();
}
appendMediaEnhancements(window.apostrophe);

function appendMediaEnhancements(apostrophe)
{
    //
    // File upload enhancements
    //
    apostrophe.fileUploader = function(defaults) {
        if ((typeof options) == 'undefined') {
            defaults = [];
        }

        var $selector = (defaults.selector)? $(defaults.selector) : $('.a-file-uploader');
        var $uploadList = (defaults.uploadListSelector)? $(defauls.uploadListSelector) : $('.a-file-upload-list');

        function combine(fn1, fn2, e, file)
        {
            if (typeof(fn2) == 'function') {
                if (typeof(fn1) == 'function') {
                    return function(e, file) {fn1(e, file);fn2(e, file);};
                }
                return fn2;
            }
            return fn1;
        }

        $selector.each(function() {
            var $this = $(this);
            var options = $.extend({}, defaults);

            // default drag handlers
            options.dragenter = combine(function(e) {
                $this.addClass('drag-over');
            }, options.dragenter);
            options.dragover = combine(function(e) {
                $this.addClass('drag-over');
            }, options.dragover);
            options.dragleave = combine(function(e) {
                $this.removeClass('drag-over');
            }, options.dragleave);
            options.dragend = combine(function(e) {
                $this.removeClass('drag-over');
            }, options.dragend);
            options.drop = combine(function(e) {
                $this.removeClass('drag-over');
            }, options.drop);

            // file upload handlers
            options.onload = combine(function(e, file) {
                var type = file.type.split('/');

                var $thumb = $('<div />');
                $thumb.addClass('a-file-upload-thumbnail');
                $thumb.addClass('a-file-upload-thumbnail-' + type[1]);
                $thumb.attr('data-filename', escape(file.name));

                // add thumbnail if available
                if (type[0] == 'image') {
                    if (window.FileReader) {
                        var reader = new FileReader();

                        reader.onload = (function(f) {
                            return function(e)
                            {
                                var $img = $('<img />');

                                var i = new Image;
                                i.src = e.target.result;
                                i.onload = function()
                                {
                                    var width = i.width * 100 / i.height;
                                    var height = i.height * width / i.width;
                                    var canvas = document.createElement("canvas");
                                    var context = canvas.getContext("2d");

                                    canvas.width = width;
                                    canvas.height = height;
                                    context.drawImage(i, 0, 0, i.width, i.height, 0, 0, width, height)

                                    $img.addClass('a-file-upload-img-thumb');
                                    $img.attr('src', canvas.toDataURL('image/png'));
                                    $thumb.append($img);
                                }
                            }
                        })(file);

                        reader.readAsDataURL(file);
                    }
                }
                $uploadList.append($thumb);
            }, options.onload);

            options.ajaxTransferSuccess = combine(function(e, file) {
               var $thumb = $('div[data-filename="' + escape(file.name) + '"]');
               $thumb.addClass('done');
            });

            $this.aFileUploader(options);
        });
    }
}
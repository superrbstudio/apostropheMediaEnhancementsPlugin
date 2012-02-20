// Apostrophe html5 file uploader
//
// Author: Wesley John-Alder
//

(function( $ ) {
    $.fn.aFileUploader = function(options) {

        var crlf = '\n';
        var boundary = "apostrophe";
        var dashes = "--";

        var defaults = $.extend({
            "name": "aFile",
            "url": "/admin/media/html5Upload",
            "maxAjaxUploads": 1,

            // Drag handlers
            "dragstart": null,
            "drag": null,
            "dragenter": null,
            "dragleave": null,
            "dragover": null,
            "drag": null,
            "dragend": null,

            // File reader handlers
            "onabort": null,
            "onerror": null,
            "onload": null,
            "onloadend": null,
            "onloadstart": null,
            "onprogress": null,

            // AJAX handlers
            "ajaxProgress": null,
            "ajaxTransferSuccess": null,
            "ajaxTransferLoad": null,
            "ajaxTransferFailed": null,
            "ajaxTransferCanceled": null,

            // Other handlers
            "invalidFile": null
        }, options);

        return this.each(function() {
            $input = $(this);

            $input.bind("dragstart.aUploader", defaults.dragstart);
            $input.bind("drag.aUploader", defaults.drag);
            $input.bind("dragenter.aUploader", defaults.dragenter);
            $input.bind("dragleave.aUploader", defaults.dragleave);
            $input.bind("dragover.aUploader", defaults.dragover);
            $input.bind("drag.aUploader", defaults.drag);
            $input.bind("dragend.aUploader", defaults.dragend);

            if ($input.is('[type="file"]')) {
                $input.bind("change", function () {
                    handleFiles(this.files);
                    //$input.closest('form').submit();
                });
            } else {
                $input.bind("drop.aUploader", function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    handleFiles(event.originalEvent.dataTransfer.files);
                    
                    return false;
                });
            }

            // This function takes multiple file inputs
            // and tries to upload each of them individually
            function handleFiles(files)
            {
                var requests = [];
                
                for (var i = 0; i < files.length; i++) {

                    var file = files[i];

                    if (file.size > 0) {
                        requests.push(upload(file));
                    }
                    else
                    {
                        if (typeof(defaults.invalidFile) == 'function')
                        {
                            defaults.invalidFile(file);
                        }
                    }

                }

                function executeNextRequest() {
                    var req = requests.shift();

                    if (typeof(req) == 'function') {
                        $.when(req()).then(function(){ executeNextRequest();});
                    }
                }

                for (var i = 0; i < defaults.maxAjaxUploads; i++)
                {
                    executeNextRequest();
                }
            }

            // Stop propagation for dragenter, dragexit, and dragover events
            function cancelEvent(event) {
                event.stopPropagation();
                event.preventDefault();

                return false;
            }
            
            $input.bind("dragenter.aUploader", function(event) {
                return cancelEvent(event);
            });

            $input.bind("dragexit.aUploader", function(event) {
                return cancelEvent(event);
            });

            $input.bind("dragover.aUploader", function(event) {
                return cancelEvent(event);
            });

            function frCall(fn, e, file)
            {
                if (typeof(fn) == 'function')
                {
                    fn(e, file);
                }
            }

            // This will work on Firefox and Chrome
            function upload(file)
            {
                // deferred object will allow us to chain events based on resolution of ajax
                var deferred = $.Deferred();
                var aSendFunction;

                // xmlHttpRequest is the easiest way to deal with posting data
                var xmlHttpRequest = new XMLHttpRequest();
                xmlHttpRequest.open("POST", defaults.url, true);
                xmlHttpRequest.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                // Ajax events
                xmlHttpRequest.onreadystatechange = function() {
                    if ((xmlHttpRequest.readyState == 4)) {
                        if (xmlHttpRequest.status == 200) {
                            var data = xmlHttpRequest.responseText;
                            if (typeof(defaults.ajaxTransferSuccess) == 'function') {
                                defaults.ajaxTransferSuccess(data, file);
                            }
                        }
                        deferred.resolve();
                    }
                }
                
                xmlHttpRequest.upload.addEventListener("progress", function(event) { frCall(defaults.ajaxProgress, event, file); }, false);
                xmlHttpRequest.upload.addEventListener("load", function(event) { frCall(defaults.ajaxTransferLoad, event, file); }, false);
                xmlHttpRequest.upload.addEventListener("error", function(event) { frCall(defaults.ajaxTransferFailed, event, file); }, false);
                xmlHttpRequest.upload.addEventListener("abort", function(event) { frCall(defaults.ajaxTransferCanceled, event, file); }, false);
                
                if (window.FileReader) { // Firefox and Chrome
                    var fileReader = new FileReader;

                    var aSendFunction = null;

                    // attach events to the FileReader
                    fileReader.onabort = function(e) {
                        frCall(defaults.onabort, e, file);
                    }

                    fileReader.onerror = function(e) {
                        frCall(defaults.onerror, e, file);
                    }

                    fileReader.onload = function(e) {
                        frCall(defaults.onload, e, file);
                    }

                    fileReader.onloadend = function(e) {
                        frCall(defaults.onloadend, e, file);
                    }

                    fileReader.onloadstart = function(e) {
                        frCall(defaults.onloadstart, e, file);
                    }

                    fileReader.onprogress = function(e) {
                        frCall(defaults.onprogress, e, file);
                    }

                    // load the file
                    fileReader.readAsDataURL(file);

                    if (file.getAsBinary) { // Firefox chrome
                        var data = dashes + boundary + crlf +
                            "Content-Disposition: form-data;" +
                            "name=\"" + defaults.name + "\";" +
                            "filename=\"" + unescape(encodeURIComponent(file.name)) + "\"" + crlf +
                            "Content-Type: application/octet-stream" + crlf + crlf +
                            file.getAsBinary() + crlf +
                            dashes + boundary + dashes;

                        xmlHttpRequest.setRequestHeader("Content-Type", "multipart/form-data;boundary=" + boundary);

                        aSendFunction = function() {
                            xmlHttpRequest.sendAsBinary(data);

                            return deferred.promise();
                        }
                    } else if (window.FormData) { // Chrome
                        var formData = new FormData();
                        formData.append(defaults.name, file);

                        aSendFunction = function() {
                            xmlHttpRequest.send(formData);

                            return deferred.promise();
                        }
                    }
                } else if (window.FormData) { // Safari
                    var formData = new FormData();
                    formData.append(defaults.name, file);

                    aSendFunction = function() {
                        xmlHttpRequest.send(formData);

                        return deferred.promise();
                    }

                }

                return aSendFunction;
            }
        });

    };
})( jQuery );
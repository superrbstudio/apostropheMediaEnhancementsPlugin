$(document).ready(function() {

    // Backbone models and views for keeping track of the uploads

    // upload item
    var MediaItem = Backbone.Model.extend({
        defaults: {
            'id': null,
            'count': null,
            'filename': null,
            'title': null,
            'mediaType': null,
            'file': null,
            'view': null,

            'viewUrl': null,
            'editUrl': null,
            'deleteUrl': null
        },

        setDone: function(data) {
            // memory management
            this.set('file', null);

            // set the new params
            this.set('id', data.item.id);
            this.set('title', data.item.title);
            this.set('viewUrl', data.viewUrl);
            this.set('editUrl', data.editUrl);
            this.set('deleteUrl', data.deleteUrl);

            this.get('view').setDone();
        },

        setError: function() {
            this.get('view').setError();
        }
    });

    // Item upload view
    var MediaItemView = Backbone.View.extend({
        tagName: 'li',
        className: 'a-file-upload-thumbnail',
        itemTemplate: _.template($('#a-tmpl-media-thumb').text()),
        imageTemplate: _.template($('#a-tmpl-media-thumb-image').text()),
        titleTemplate: _.template($('#a-tmpl-media-upload-title').text()),

        events: {
            'click .a-upload-edit':     'edit'
        },

        initialize: function() {
            var self = this;
            this.model.set('view', self);
        },

        render: function() {
            var params = {};
            params.media_type = this.model.get('mediaType');

            this.$el.html(this.itemTemplate(params));

            return this;
        },

        showImage: function(imageData) {
            var params = {};
            params.image_data = imageData;

            this.$el.append($(this.imageTemplate(params)));
        },

        setDone: function() {
            this.$el.removeClass('error').addClass('done');

            var params = {};
            params.item_title = this.model.get('title');
            params.view_url = this.model.get('viewUrl');
            params.edit_url = this.model.get('editUrl');
            params.delete_url = this.model.get('deleteUrl');

            this.$el.append($(this.titleTemplate(params)));
        },

        setError: function() {
            this.$el.addClass('error');
        },

        edit: function(event) {
            event.preventDefault();
            console.log(this.model.get('title') + ".edit()");
            

            return false;
        }
    });

    // Upload List
    var MediaItemCollection = Backbone.Collection.extend({
        model: MediaItem,

        findByFile: function(file) {
            return this.find(function(item) {
                return item.get('file') === file;
            });
        }
    });

    // Upload list view
    var MediaItemCollectionView = Backbone.View.extend({
        tagName: 'ul',
        className: 'a-file-upload-list',

        widget: null,
        collection: null,
        _MediaItemViews: {},

        initialize: function() {
            _(this).bindAll('add');

            this.collection.bind('add', this.add);
            this.render();
        },

        render: function() {
            this.options.widget.after(this.$el);
            return this;
        },

        add: function(item) {
            var itemView = new MediaItemView({
                model: item
            });

            this._MediaItemViews[item.get('count')] = itemView;
            this.$el.append(itemView.$el);
        }
    });



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
            var defaults = $.extend({
                'fileUploaderSelector': 'a-file-uploader',
                'fileListSelector': 'a-file-upload-list'
            }, defaults);

            var $selector = (defaults.selector)? $(defaults.selector) : $('.' + defaults.fileUploaderSelector);
            var $uploadList = (defaults.uploadListSelector)? $(defauls.uploadListSelector) : $('.' + defaults.fileListSelector);

            function combine(fn1, fn2, e, file)
            {
                if (typeof(fn2) == 'function') {
                    if (typeof(fn1) == 'function') {
                        return function(e, file) { fn1(e, file); fn2(e, file);};
                    }
                    return fn2;
                }
                return fn1;
            }

            $selector.each(function() {
                var $this = $(this);
                var options = $.extend({}, defaults);
                var fileCount = 0;

                var mediaItems = new MediaItemCollection();
                var mediaItemsView = new MediaItemCollectionView({
                    collection: mediaItems,
                    widget: $this
                });


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
                // before load
                options.beforeHandle = combine(function(e, file) {
                    var type = file.type.split('/');

                    var params = {};
                    params.mediaType = type[1];
                    params.filename = escape(file.name);
                    params.file = file;
                    params.count = fileCount++;

                    var item = new MediaItem(params);
                    mediaItems.add(item);

                }, options.beforeHandle);

                // on image load
                options.onload = combine(function(e, file) {
                    var type = file.type.split('/');
                    var itemView = mediaItems.findByFile(file).get('view');

                    // add thumbnail if available
                    if (type[0] == 'image') {
                        if (window.FileReader) {
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

                                itemView.showImage(canvas.toDataURL('image/png'));
                            }
                        }
                    }
                }, options.onload);

                // on success
                options.ajaxTransferSuccess = combine(function(data, file) {
                    var item = mediaItems.findByFile(file);
                    data = $.parseJSON(data);
                    if (data.status == 'success') {
                        // Change thumbnail class
                        item.setDone(data);
                    }
                }, options.ajaxTransferSuccess);

                // on failure
                options.ajaxTransferFail = combine(function(data, file) {
                    var item = mediaItems.findByFile(file);
                    item.setError();
                }, options.ajaxTransferSuccess);

                // Set up thew widget
                $this.aFileUploader(options);
            });
        }
    }
});
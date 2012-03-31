$(document).ready(function() {

    // some stuff i'm putting in that Wes should refactor :)
    function jakesexampleforwes() {
      var dragBox = $('.a-file-uploader-dragbox');

      $(window).on('dragenter', function(event) {
        event.preventDefault();
        dragBox.addClass('drag-over');
      });
      
      $(window).on('dragend', function(event) {
        event.preventDefault();
        dragBox.removeClass('drag-over');
      });
    };

    jakesexampleforwes();

    // Backbone models and views for keeping track of the uploads

    // upload item
    var MediaItem = Backbone.Model.extend({
        defaults: {
            'id': null,
            'count': null,
            'filename': null,
            'title': null,
            'description': null,
            'credit': null,
            'is_secure': null,
            'tags': null,
            'categories': null,
            'mediaType': null,
            'file': null,
            'view': null,
            'type': null,

            'viewUrl': null,
            'editUrl': null,
            'deleteUrl': null
        },

        setDone: function(data) {
            // set the new params
            this.updateValues(data);

            this.get('view').setDone();
        },

        updateValues: function(data) {
            this.set(data);
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
        editTemplate:  _.template($('#a-upload-edit-form').text()),

        events: {
            'click .a-upload-edit':         'edit',
            'click .a-upload-delete':       'del',
            'submit .a-upload-edit-form':   'submit',
            'click .a-upload-cancel':       'hideForm'
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

            this.$el.prepend($(this.imageTemplate(params)));
        },

        setDone: function() {
            this.$el.removeClass('error').addClass('done');
            this.updateValues();

            // Safari and IE got no pre-preview
            if (!window.FileReader && (this.model.get('type') == 'image') && (this.model.get('srcUrl'))) {
                this.showImage(this.model.get('srcUrl'));
            }
        },

        updateValues: function() {
            this.hideForm();
            this.$el.find('.a-media-upload-controls').remove();

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
            aLog(this.model.get('title') + ".edit()");

            if (this.formOpen) { return; }

            var params = {};
            params.id = this.model.get('id');
            params.title = this.model.get('title');
            params.description = this.model.get('description');
            params.credit = this.model.get('credit');
            params.is_secure = this.model.get('is_secure');
            params.tags = this.model.get('tags');
            params.categories = this.model.get('categories');
            params.allCategories = apostrophe.allCategories;

            this.$el.append($(this.editTemplate(params)));
            this.formOpen = true;

            pkInlineTaggableWidget(this.$el.find('.a-upload-tags-input'), {'popular-tags': apostrophe.popularTags, 'all-tags': apostrophe.allTags, 'typeahead-url': apostrophe.typeheadUrl, 'commit-selector': this.$el.find('input[type=submit]')});
            aMultipleSelect(this.$el, {'choose-one': 'Select One', 'add': 'New Category'});

            this.$el.addClass('editing');

            return false;
        },

        hideForm: function() {
            this.$el.removeClass('editing');
            this.$el.find('.a-upload-form-container').remove();
            this.formOpen = false;
        },

        del: function(event) {
            event.preventDefault();
            aLog(this.model.get('title') + "View.remove()");

            if (confirm('Are you sure?')) {
                var me = this;

                $.ajax({
                    url: me.model.get('deleteUrl'),
                    dataType: 'json',
                    success: function(data) {
                        if (data && data.status && (data.status == 'success')) {
                            me.collection.remove(me.model);
                        }
                    }
                });
            }

            return false;
        },

        submit: function(event) {
            event.preventDefault();
            aLog(this.model.get('title') + 'View.submit()');

            var me = this;
            var $target = $(event.target);
            var formData = $target.serialize();
            var postUrl = this.model.get('editUrl');

            $.ajax({
                url: postUrl,
                data: formData,
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status && (data.status == 'success')) {
                        me.model.updateValues(data);
                        me.updateValues();

                        apostrophe.getAllCategories();
                        apostrophe.getAllTags();
                        apostrophe.getPopularTags();
                    }
                }
            });


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
        _MediaItemViews: [],

        initialize: function() {
            _(this).bindAll('add', 'remove');

            this.collection.bind('add', this.add);
            this.collection.bind('remove', this.remove);
            
            this.options.widget.after(this.$el);
        },

        render: function() {
            var me = this;
            aLog('MediaItemCollectionView.render()');

            me.$el.empty();
            _(this.MediaItemViews).each(function(item) {
                me.$el.append(item.get('view').$el);
            });
            
            return this;
        },

        add: function(item) {
            var me = this;

            var itemView = new MediaItemView({
                model: item,
                collection: me.collection
            });

            this._MediaItemViews.push(itemView);
            this.$el.append(itemView.$el);
        },

        remove: function(model) {
            var childView = _(this._MediaItemViews).select(function(cv) {
                return cv.model === model;
            })[0];
            this._MediaItemViews = _(this._MediaItemViews).without(childView);

            childView.remove();
        }
    });



    if (!window.apostrophe)
    {
        window.apostrophe = new aConstructor();
    }
    appendMediaEnhancements(window.apostrophe);

    function appendMediaEnhancements(apostrophe)
    {
        // Taggable widget enhancements
        apostrophe.popularTagsUrl = '';
        apostrophe.popularTags = [];
        apostrophe.allTagsUrl = '';
        apostrophe.allTags = [];
        apostrophe.allCategoriesUrl = '';
        apostrophe.allCategories = [];

        apostrophe.setPopularTagsUrl = function(getUrl) {
            apostrophe.popularTagsUrl = getUrl;
        };

        apostrophe.setAllTagsUrl = function(getUrl) {
            apostrophe.allTagsUrl = getUrl;
        };

        apostrophe.setAllCategoriesUrl = function(getUrl) {
            apostrophe.allCategoriesUrl = getUrl;
        };

        apostrophe.getPopularTags = function() {
            return $.ajax({
                url: apostrophe.popularTagsUrl,
                dataType: 'json',
                success: function(data) {
                    apostrophe.popularTags = data;
                }
            });
        };

        apostrophe.getAllTags = function() {
            return $.ajax({
                url: apostrophe.allTagsUrl,
                dataType: 'json',
                success: function(data) {
                    apostrophe.allTags = data;
                }
            });
        };

        apostrophe.getAllCategories = function() {
            return $.ajax({
                url: apostrophe.allCategoriesUrl,
                dataType: 'json',
                success: function(data) {
                    apostrophe.allCategories = data;
                }
            });
        };

        apostrophe.typeaheadUrl = '';

        apostrophe.setTypeaheadUrl = function(url) {
            apostrophe.typeaheadUrl = url;
        }
        

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
                        return function(e, file) {fn1(e, file);fn2(e, file);};
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
                }, options.ajaxTransferFail);

                // Set up thew widget
                $this.aFileUploader(options);
            });
        }
    }
});
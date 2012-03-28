<script id="a-tmpl-media-thumb" type="text/template">
    <li class="a-file-upload-thumbnail <%= media_type %>">
    </li>
</script>

<script id="a-tmpl-media-thumb-image" type="text/template">
    <img class="a-file-upload-image" src="<%= image_data %>" />
</script>

<script id="a-tmpl-media-upload-title" type="text/template">
    <div class="a-media-upload-controls">
        <a href="<%= view_url %>">
            <span class="a-media-upload-title"><%= item_title %></span>
        </a>
        <br />
        <a class="a-upload-edit" href="<%= edit_url %>">
            Edit
        </a>
        <br />
        <a class="a-upload-delete" href="<%= delete_url %>">
            Delete
        </a>
    </div>
</script>

<script id="a-upload-edit-form" type="text/template">
    <div class="a-upload-form-container">
        Edit <%= title %>
        <form class="a-upload-edit-form">
            <ul>
                <li>
                    Title <input type="input" name="media_item[title]" value="<%= title %>" />
                </li>
                <li>
                    Description <textarea name="media_item[description]"><%= description %></textarea>
                </li>
                <li>
                    Credit <input type="input" name="media_item[credit]" value="<%= credit %>" />
                </li>
                <li>
                    Categories:
                    <select multiple="multiple" name="media_item[categories][]">
                        <% _.each(allCategories, function(c) { %>
                            <option value="<%= c.id %>" <% if (_(categories).find(function(cat) {
                                    return cat.id === c.id;
                                })) { %> selected="selected" <% } %>><%= c.name %></option>
                        <% }); %>
                    </select>
                </li>
                <li>
                    Tags <input type="input" class="a-upload-tags-input" name="media_item[tags]" value="<%= tags %>" />
                </li>
                <li>
                    <ul>
                        <li>
                            Public <input type="radio" name="media_item[is_secure]" <% if (!obj.is_secure) { %> checked="checked" <% } %> value="0" />
                        </li>
                        <li>
                            Hidden <input type="radio" name="media_item[is_secure]" <% if (obj.is_secure) { %> checked="checked" <% } %> value="1" />
                        </li>
                    </ul>
                </li>
            </ul>

            <input type="submit" class="a-upload-submit-<%= id %>" />
        </form>
        <a href="#" class="a-upload-cancel">Cancel</a>
    </div>
</script>


<script id="a-upload-batch-edit-form" type="text/template">
</script>

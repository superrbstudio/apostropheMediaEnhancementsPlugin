<script id="a-tmpl-media-thumb" type="text/template">
    <li class="a-file-upload-thumbnail <%= media_type %>">
    </li>
</script>

<script id="a-tmpl-media-thumb-image" type="text/template">
    <div class="a-file-upload-image-item">
      <img class="a-file-upload-image" src="<%= image_data %>" />
    </div>
</script>

<script id="a-tmpl-media-upload-title" type="text/template">
    <div class="a-ui a-media-upload-controls">
        <div class="a-media-upload-title">
          <a href="<%= view_url %>">
              <%= item_title %>
          </a>
        </div>

        <div class="a-controls">
          <a class="a-btn icon a-edit alt lite a-upload-edit" href="<%= edit_url %>">
          <span class='icon'></span> Edit
          </a>
          <a class="a-btn icon a-delete alt lite a-upload-delete" href="<%= delete_url %>">
           <span class='icon'></span> Delete
          </a>          
        </div>
    </div>
</script>


<script id="a-upload-edit-form" type="text/template">
    <div class="a-upload-form-container">
        Edit <%= title %>
        <form class="a-upload-edit-form">
            <ul>
                <li class="a-form-row">
                  <label>Title</label>
                    <input type="input" name="media_item[title]" value="<%= title %>" />
                </li>
                <li class="a-form-row">
                  <label>Description</label>
                    <textarea name="media_item[description]"><%= description %></textarea>
                </li>
                <li class="a-form-row">
                  <label>Credit</label>
                  <input type="input" name="media_item[credit]" value="<%= credit %>" />
                </li>
                <li class="a-form-row">
                    <label>Categories:</label>
                    <select multiple="multiple" name="media_item[categories][]">
                        <% _.each(allCategories, function(c) { %>
                            <option value="<%= c.id %>" <% if (_(categories).find(function(cat) {
                                    return cat.id === c.id;
                                })) { %> selected="selected" <% } %>><%= c.name %></option>
                        <% }); %>
                    </select>
                </li>
                <li class="a-form-row">
                    <label>Tags</label>
                    <input type="input" class="a-upload-tags-input" name="media_item[tags]" value="<%= tags %>" />
                </li>
                <li class="a-form-row-radio">
                    <ul>
                        <li class="a-form-row">
                          <label>Public</label>
                          <input type="radio" name="media_item[is_secure]" <% if (!obj.is_secure) { %> checked="checked" <% } %> value="0" />
                        </li>
                        <li class="a-form-row">
                            <label>Hidden</label>
                            <input type="radio" name="media_item[is_secure]" <% if (obj.is_secure) { %> checked="checked" <% } %> value="1" />
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

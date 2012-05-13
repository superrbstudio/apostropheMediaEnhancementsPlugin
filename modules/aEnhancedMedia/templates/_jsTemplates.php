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
          <a class="a-btn icon a-edit alt lite no-label a-upload-edit" href="<%= edit_url %>">
          <span class='icon'></span> Edit
          </a>
          <a class="a-btn icon a-delete alt lite no-label a-upload-delete" href="<%= delete_url %>">
           <span class='icon'></span> Delete
          </a>
        </div>
    </div>
</script>

<script id="a-upload-edit-form" type="text/template">
    <div class="a-upload-form-container">
        <h4>Editing <strong><%= title %></strong></h4>
        <form class="a-ui a-media-edit-form a-upload-edit-form">
                <div class="a-form-row title">
                  <label>Title</label>
                  <div class="a-form-field">
                    <input type="text" name="media_item[title]" value="<%= title %>" />
                  </div>
                </div>
                <div class="a-form-row description">
                  <label>Description</label>
                  <div class="a-form-field">
                    <textarea name="media_item[description]"><%= description %></textarea>
                  </div>
                </div>
                <div class="a-form-row credit">
                  <label>Credit</label>
                  <div class="a-form-field">
                    <input type="text" name="media_item[credit]" value="<%= credit %>" />
                  </div>
                </div>
                <div class="a-form-row categories">
                    <label>Categories:</label>
                    <div class="a-form-field">
                      <select multiple="multiple" name="media_item[categories_list][]">
                          <% _.each(allCategories, function(c) { %>
                              <option value="<%= c.id %>" <% if (_(categories).find(function(cat) {
                                      return cat.id === c.id;
                                  })) { %> selected="selected" <% } %>><%= c.name %></option>
                          <% }); %>
                      </select>
                    </div>
                </div>
                <div class="a-form-row tags">
                    <label>Tags</label>
                    <div class="a-form-field">
                      <input type="input" class="a-upload-tags-input" name="media_item[tags]" value="<%= tags %>" />
                    </div>
                </div>
                <div class="a-form-row permissions">
                  <label for="media_item[view_is_secure]">Permissions</label>
                  <div class="a-form-field">
                    <ul class="radio_list">
                        <li>
                          <input type="radio" name="media_item[view_is_secure]" id="a_media_item_330_view_is_secure_0" <% if (!obj.view_is_secure) { %> checked="checked" <% } %> value="0" >&nbsp;<label for="a_media_item_330_view_is_secure_0">Public</label>
                        </li>
                        <li>
                            <input type="radio" name="media_item[view_is_secure]" id="a_media_item_330_view_is_secure_1" <% if (obj.view_is_secure) { %> checked="checked" <% } %> value="1" >&nbsp;<label for="a_media_item_330_view_is_secure_1">Hidden</label>
                        </li>
                    </ul>
                  </div>
                  <div class="a-help">
                  Permissions: Hidden Photos can be used in photo slots, but are not displayed in the Media section.
                  </div>
                </div>
            <ul class="a-ui a-controls a-align-left bottom">
              <li><input type="submit" class="a-btn a-submit a-upload-submit-<%= id %>" /></li>
              <li><a href="#" class="a-btn icon a-cancel alt a-upload-cancel"><span class="icon"></span>Cancel</a></li>
            </ul>
        </form>
    </div>
</script>

<script id="a-upload-batch-edit-form" type="text/template">
</script>

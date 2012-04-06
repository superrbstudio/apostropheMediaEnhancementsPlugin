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
        <form class="a-upload-edit-form">
            <ul>
                <li class="a-form-row">
                  <label>Title</label>
                  <div class="a-form-field">
                    <input type="input" name="media_item[title]" value="<%= title %>" />
                  </div>
                </li>
                <li class="a-form-row">
                  <label>Description</label>
                  <div class="a-form-field">
                    <textarea name="media_item[description]"><%= description %></textarea>
                    </div>
                </li>
                <li class="a-form-row">
                  <label>Credit</label>
                  <div class="a-form-field">
                  <input type="input" name="media_item[credit]" value="<%= credit %>" />
                  </div>
                </li>
                <li class="a-form-row">
                    <label>Categories:</label>
                    <div class="a-form-field">
                      <select multiple="multiple" name="media_item[categories][]">
                          <% _.each(allCategories, function(c) { %>
                              <option value="<%= c.id %>" <% if (_(categories).find(function(cat) {
                                      return cat.id === c.id;
                                  })) { %> selected="selected" <% } %>><%= c.name %></option>
                          <% }); %>
                      </select>
                    </div>
                </li>
                <li class="a-form-row">
                    <label>Tags</label>
                    <div class="a-form-field">
                    <input type="input" class="a-upload-tags-input" name="media_item[tags]" value="<%= tags %>" />
                    </div>
                </li>
                <li class="a-form-row-radio">
                    <ul>
                        <li class="a-form-row">
                          <label>Public</label>
                          <div class="a-form-field">
                          <input type="radio" name="media_item[view_is_secure]" <% if (!obj.view_is_secure) { %> checked="checked" <% } %> value="0" />
                          </div>
                        </li>
                        <li class="a-form-row">
                            <label>Hidden</label>
                            <div class="a-form-field">
                            <input type="radio" name="media_item[view_is_secure]" <% if (obj.view_is_secure) { %> checked="checked" <% } %> value="1" />
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
            
            <input type="submit" class="a-btn a-submit a-upload-submit-<%= id %>" />
            <a href="#" class="a-btn icon a-cancel alt a-upload-cancel"><span class="icon"></span>Cancel</a>
        </form>
    </div>
</script>

<script id="a-upload-batch-edit-form" type="text/template">
</script>

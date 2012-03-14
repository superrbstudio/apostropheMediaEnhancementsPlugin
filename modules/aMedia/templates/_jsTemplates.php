<script id="a-tmpl-media-thumb" type="text/template">
    <div class="a-file-upload-thumbnail">
    </div>
</script>

<script id="a-tmpl-media-upload-thumb" type="text/template">
    <img class="a-file-upload-image-preview" />
</script>

<script id="a-tmpl-media-upload-title" type="text/template">
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
    <div class="a-upload-edit-form"></div>
</script>
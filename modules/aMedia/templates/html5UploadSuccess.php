<h1>Drag and Drop Upload!  -- REMIX</h1>

<form>
    <input id="fileupload" type="file" name="aFile[]" multiple>
</form>


<?php /*
<div id="fileupload" style="width: 100px; height: 100px; border: thin solid #000;">Files!</div>

*/
?>


<script type="text/javascript">


$(document).ready(function(e) {
    $('#fileupload').aFileUploader({
        'dragenter': function () { console.log("dragenter"); },
        'dragleave': function () { console.log("dragleave"); },
        'invalidFile': function (file) { console.log(file.name + " is invalid."); },
        'ajaxTransferSuccess': function(data) { console.log(data); }
    });
});

</script>

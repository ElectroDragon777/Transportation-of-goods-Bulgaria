<h2>Upload an Image</h2>
<form id="uploadForm">
    <input type="hidden" id="input-id" value="<?php echo $tpl['product']['id']; ?>" />
    <input type="file" id="imageFile" name="file" accept="image/*" />
</form>
<div id="uploadStatus"></div>

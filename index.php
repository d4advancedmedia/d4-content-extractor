<?php

echo '<style>
#extractor-wrap {
  background: #efefef none repeat scroll 0 0;
  color: #333;
  font-family: sans-serif;
  line-height: 28px;
  margin: 0 auto;
  max-width: 980px;
  padding: 80px 30px;
}
label {
  border-top: 1px solid #ccc;
  display: block;
  margin-top: 5px;
  padding-top: 5px;
}
textarea, input[type="text"] {
  border: 1px solid #cdcdcd;
  min-width: 350px;
  padding: 4px 8px;
}
textarea {
  min-height: 300px;
  min-width: 100%;
}
</style>';

//Set up variables from form data
$main_content = $_POST['main_content'];
$main_content_type = $_POST['main_content_type'];
$page_title = $_POST['page_title'];
$page_title_type = $_POST['page_title_type'];
$new_base_url = $_POST['new_url'];
$old_base_url = $_POST['old_url'];
$url_list = $_POST['url_list'];
$f_imagewrap = $_POST['f_imagewrap'];
$f_imagewrap_type = $_POST['f_imagewrap_type'];
$f_image_fallback = $_POST['f_image_fallback'];

//Content Migrator Form
echo '<div id="extractor-wrap"><h1>D4 Content Extractor</h1>';
$output = '<form id="extractor-form" action="index.php" method="post">'.
	'<label>Enter the id or classname of the main content div: </label><input value="'.$main_content.'" type="text" name="main_content"><br/>'.

	'<label>Check if classname was used above: </label><input name="main_content_type" value="useclass" type="checkbox"';
		if ($main_content_type == 'useclass') {$output .= "checked='checked'";}
		$output .='><br/>'.

	'<label>Enter the id or classname of the title: </label><input type="text" value="'.$page_title.'" name="page_title"><br/>'.

	'<label>Check if classname was used above: </label><input name="page_title_type" value="useclass" type="checkbox"';
		if ($page_title_type == 'useclass') {$output .= "checked='checked'";}
		$output .='><br/>'.

	'<label>Enter the id or classname of the wrapper containing the featured image: </label><input type="text" value="'.$f_imagewrap.'" name="f_imagewrap"><br/>'.

	'<label>Check if classname was used above: </label><input name="f_imagewrap_type" value="useclass" type="checkbox"';
		if ($f_imagewrap_type == 'useclass') {$output .= "checked='checked'";}
		$output .='><br/>'.

	'<label>Enter the URL for a fallback image, in the event that a featured image is corrupt: </label><input type="text" value="'.$f_image_fallback.'" name="f_image_fallback"><br/>'.	

	'<label>Old Site URL: </label><input type="text" value="'.$old_base_url.'" name="old_url"><br/>'.

	'<label>New Site URL: </label><input type="text" value="'.$new_base_url.'" name="new_url"><br/>'.

	'<label>URLs To Crawl (one pre line)</label><textarea name="url_list">'.$url_list.'</textarea><br/>'.

	'<input text="Go!" type="submit"></form>';

echo $output;

include('content-extractor.php');
echo '</div>';
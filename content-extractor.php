<?php

if (strlen($url_list)==0) {
  echo 'Please fill out all of the fields and hit submit!';
  exit;
}

if (strlen($url_list)!=0) {

$url_array = explode("\n", str_replace("\r", "", $url_list));

/*echo $main_content.'</br>';
echo $main_content_type.'</br>';
echo $page_title.'</br>';
echo $page_title_type.'</br>';
echo $new_base_url.'</br>';
echo $old_base_url.'</br>';*/

$xml = '';
// Init the '$url_array' array.
#$url_array = file('migration_urls.txt');
#$url_array = array('http://www.nevadaemployers.org/about-nae/','http://www.nevadaemployers.org/what-we-do/');
// Roll through the '$url_array' array.

$i = 0;
$media_id = 926;
echo '<br/><br/><strong>Use the following redirects in your .htaccess file:</strong><br/><ol>';
foreach ($url_array as $line=>$url_value) {
	$i++;
  	$doc = new DOMDocument;
  	libxml_use_internal_errors(true);

  	//Get User ID from URL
  	$parts=parse_url($url_value);
  	$path_parts=explode('/', $parts['path']);
  	$author = $path_parts[2];


	// Remove whitespace
	$doc->preserveWhiteSpace = false;

	//Ignore invalid HTML markup
	$doc->strictErrorChecking = false;
	$doc->recover = true;

	$doc->loadHTMLFile($url_value);
	libxml_clear_errors();

	//Set elements to fetch
	if ($main_content_type != 'useclass') {
		$single_content = $doc->getElementById($main_content);
	} else {
		//Get DOM elements by classname
		$classfinder = new DomXPath($doc);
		$classnames = array($main_content);

		foreach ($classnames as $classname) {
			$nodes = $classfinder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");			
			//Remove unwanted nodes with class name specified above
			foreach($nodes as $e ) {
			    // Delete this node
			    $single_content = $e;
			}
		}
	}

	//Set elements to fetch
	if ($page_title_type != 'useclass') {
		$single_title = $doc->getElementById($page_title);
	} else {
		//Get DOM elements by classname
		$classfinder = new DomXPath($doc);
		$classnames = array($page_title);

		foreach ($classnames as $classname) {
			$nodes = $classfinder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");			
			//Remove unwanted nodes with class name specified above
			foreach($nodes as $e ) {
			    // Delete this node
			    $single_title = $e;
			}
		}
	}

	//Set elements to fetch
	if ($f_imagewrap_type != 'useclass') {
		$featured_image_wrap = $doc->getElementById($f_imagewrap);
	} else {
		//Get DOM elements by classname
		$classfinder = new DomXPath($doc);
		$classnames = array($f_imagewrap);

		foreach ($classnames as $classname) {
			$nodes = $classfinder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");			
			//Remove unwanted nodes with class name specified above
			foreach($nodes as $e ) {
			    // Delete this node
			    $featured_image_wrap = $e;
			}
		}
	}

	$featured_image = '';
	if (is_object($featured_image_wrap)) {
		$featured_image = $featured_image_wrap->getElementsByTagName('img');
	}
	
	/*$single_specs = $doc->getElementById('car_specs');
	$featured_image_wrap = $doc->getElementById('featured_image');
	$gallery_wrap = $doc->getElementById('myCarouselThumbs');
	$video_wrap = $doc->getElementById('myCarousel');*
	$featured_image = '';
	$gallery_images = '';
	$video = '';
	if (is_object($featured_image_wrap)) {
		$featured_image = $featured_image_wrap->getElementsByTagName('img');
	}
	if (is_object($gallery_wrap)) {
		$gallery_images = $gallery_wrap->getElementsByTagName('img');
	}
	if (is_object($video_wrap)) {
		$video = $video_wrap->getElementsByTagName('iframe');
	}
	$title_tag = $doc->getElementsByTagName('h1');*/

	/*Get DOM elements by classname
	$classfinder = new DomXPath($doc);
	$classnames = array('related-terms','links','section-date-author');

	foreach ($classnames as $classname) {
		$nodes = $classfinder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		//Remove unwanted nodes with class name specified above
		foreach($nodes as $e ) {
		    // Delete this node
		    $e->parentNode->removeChild($e);
		}
	}

	foreach ($title_tag as $title) {
		//echo '<li>'.$title->nodeValue.'</li>';
    	$single_title = $title->nodeValue;
    	break;
	}*/

	$single_content_html = '';
	$single_title_html = '';
	$image_html = '';
	/*$single_specs_html = '';
	$image_html = '';
	$image_src = '';
	$gallery_src = '';
	$vide_src = '';*/
	if ($single_content != '') {
		foreach ($single_content->childNodes as $c) {
	    	$single_content_html .= $c->ownerDocument->saveXML($c);
		}
	}	
	if ($single_title != '') {
		$single_title_html = '';
		foreach ($single_title->childNodes as $c) {
	    	$single_title_html .= $c->ownerDocument->saveXML($c);
		}
	}
	if ($featured_image != '') {
		$image_src = '';
		foreach ($featured_image as $c) {
			$image_html = $c->ownerDocument->saveXML($c);
			preg_match( '@src="([^"]+)"@' , $image_html, $match );
			$image_src = array_pop($match);
			if (!strpos($image_src,'http')) {
				$image_src = $old_base_url.$image_src;
			}
			if (!getimagesize($image_src)) {
				$image_src = $f_image_fallback;
			}
		}
	} else {
		$image_src = $f_image_fallback;
	}
	/*if ($single_specs != '') {
		$single_specs_html = '';
		foreach ($single_specs->childNodes as $c) {
	    	$single_specs_html .= $c->ownerDocument->saveXML($c);
		}
	}
	if ($featured_image != '') {
		$image_src = '';
		foreach ($featured_image as $c) {
			$image_html = $c->ownerDocument->saveXML($c);
			preg_match( '@src="([^"]+)"@' , $image_html, $match );
			$image_src = array_pop($match);
		}
	} else {
		$image_src = 'defaultimage.png';
	}
	if ($video != '') {
		$video_src = '';
		foreach ($video as $c) {
			$video_html = $c->ownerDocument->saveXML($c);
			preg_match( '@src="([^"]+)"@' , $video_html, $match );
			$video_src = array_pop($match);
		}
	}*/

	/*$featured_gallery_ids = '';
	if ($gallery_images != '') {
		$gallery_src = '';

		//Skip the first and last images in the array. The first is the featured image, which is already there, and the last is a youtube screenshot (sux)
		$last_iteration = $gallery_images->length;

		if ($video_src != '') {
			$secondtolast_iteration = $last_iteration - 1;
		} else {
			$secondtolast_iteration = $last_iteration;
		}
		$iteration = 1;
		foreach ($gallery_images as $gallery_image) {

			if ( ($iteration != 1) && ($iteration != $last_iteration) ) {
				$gallery_html = $gallery_image->ownerDocument->saveXML($gallery_image);
				preg_match( '@src="([^"]+)"@' , $gallery_html, $gallery_match );

				$single_src = array_pop($gallery_match);
				//check size of image - this will prevent the adding of corrupted images
				if (getimagesize($single_src)) {


					//check for duplicates

					
					if (strpos($gallery_src, $image_filename) === false) {

						$gallery_src .= '<img_gallery_item>'.$single_src.'</img_gallery_item>';
						$media_id++;
						if ($iteration == $secondtolast_iteration) {
							$featured_gallery_ids .= $media_id;
						}
						else {
							$featured_gallery_ids .= $media_id.',';
						}

					}
				}
			}

			$iteration++;
		}
	}

	$featured_gallery = '<featured_gallery>'.$featured_gallery_ids.'</featured_gallery>';*/
	
	//Remove all tags, keeping the ones listed. Helps clean up content.
	$content_striptags = strip_tags($single_content_html, '<a><li><ul><h1><h2><h3><strong><p><table><thead><td><tr><th><tbody><time>');
	$title_striptags = strip_tags($single_title_html, '<a><li><ul><h1><h2><h3><strong><p><table><thead><td><tr><th><tbody><time>');
	#$specs_striptags = strip_tags($single_specs_html, '<a><li><ul><h1><h2><h3><strong><p><table><thead><td><tr><th><tbody><time>');

	//Remove inline styling and then empty p tags (arrays ordered accordingly)
	//NOTE: To replace all empty tags, use: //$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";
	$patterns = array ('/(<[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '/<p[^>]*><\\/p[^>]*>/');
	$replace = array ('$1$3', '');
	$content_html_clean = preg_replace($patterns, $replace, $content_striptags);
	$title_html_clean = preg_replace($patterns, $replace, $title_striptags);
	

	//Build single XML element
	$xml .= '<item><item_id>'.$i.'</item_id><post_title><![CDATA['.$title_html_clean.']]></post_title><post_content><![CDATA['.$content_html_clean.']]></post_content><featured_image>'.$image_src.'</featured_image></item>';

	//create a list of 301 redirects, this will be echoed on the results page
	 //Lower case everything
    $new_url = strtolower($title_html_clean);
    //Make alphanumeric (removes all other characters)
    $new_url = preg_replace("/[^a-z0-9_\s-]/", "-", $new_url);
    //Clean up multiple dashes or whitespaces
    $new_url = preg_replace("/[\s-]+/", " ", $new_url);
    //Convert whitespaces and underscore to dash
    $new_url = preg_replace("/[\s_]/", "-", $new_url);
    //Remove any dashes or spaces from the beginning and end of the string
    $new_url = trim($new_url,'- ');
    $new_url = $new_base_url.'/'.$new_url;

	$url_value = str_replace($old_base_url, "", $url_value);
	echo 'Redirect 301 '.$url_value.' '.$new_url.'<br/>';
	

}
echo '</ol>'.
'<br/>'.
'<h3>Extraction Complete!</h3>';
//Generate the complete XML document and save into an output file
$output = '<?xml version="1.0" encoding="UTF-8"?><root>'.$xml.'</root>';
$xml_url = 'extraction_'.time().'.xml';
echo 'Extraction Results: <a target="_blank" href="'.$xml_url.'">'.$xml_url.'</a>';
file_put_contents('extraction_'.time().'.xml', $output);

}
?>
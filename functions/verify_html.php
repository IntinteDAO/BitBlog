<?php

function verify_html($html) {

	$allowed_tags = array("html", "body", "blockquote", "pre", "h1", "h2", "h3", "h4", "h5", "h6", "br", "b", "u", "span", "p", "font", "ul", "ol", "li", "div", "table", "tbody", "tr", "td", "a", "img", "iframe");
	$allowed_attrs = array("class", "id", "style", "color", "data-darkreader-inline-bgcolor", "align", "href", "target", "src", "data-filename", "width", "height", "frameborder");

	$dom = new DOMDocument;
	$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

	foreach ($dom->getElementsByTagName("*") as $tag) {

		if(!in_array($tag->tagName, $allowed_tags)) {
			$tag->parentNode->removeChild($tag);
		} else {
			foreach ($tag->attributes as $attr) {

				if (!in_array($attr->nodeName, $allowed_attrs)) {
					$tag->removeAttribute($attr->nodeName);
				}
			}
		}
	}

	$xpath = new DOMXPath($dom);
	return $dom->saveHTML();
}
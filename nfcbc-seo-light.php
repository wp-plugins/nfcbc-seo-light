<?php
/* 
Plugin Name:  NFCBC SEO Light
Plugin URI:   http://www.fob-marketing.de/marketing-seo-blog-kategorie/internet/wordpress/my-wordpress-plugins/
Description:  Nofollow Case by Case light version for Comment Author URLs only: No link for very small comments, nofollow link for small comments, follow links for larger comments, pings and trackback links. Defaults can be replaced. 
Version:      1.0
Author:       Oliver Bockelmann
Author URI:   http://www.fob-marketing.de/
*/

/*
// NFCBC SEO Light
//
// made by fob marketing (Oliver bockelmann)
// http://www.fob-marketing.de/
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************
*/

function nfcbc_make_follow($ret) {
  global $comment;
  
/* ********** Want to change something?  *************** */
// No link for comments smaller than how many characters? 
$nfcbc_nolink = '50';

// Nofollow Link for comments smaller than how many characters?
$nfcbc_follow = '170';
/* ****************************************************** */

	if (eregi('dontfollow', $ret)) {
    	$ret = preg_replace("/\/dontfollow'/","' ",  $ret);
    	$ret = preg_replace("/\/dontfollow\"/","\" ",$ret);
		$stop = '1';

	} elseif (eregi('nolink', $ret)) {
		$ret = preg_replace("#<a [^>]+?>([^>]+?)</a>#i", "$1",$ret);
		$stop = '1';

  	} elseif (eregi('thisfollow', $ret)) {

		if (strpos($ret, $_SERVER['SERVER_NAME']) !== false) {
  			$ret = preg_replace("/ rel='external nofollow'/","",$ret);
			$ret = preg_replace("/ rel=\"external nofollow\"/","",$ret);
  			$ret = preg_replace("/ rel='nofollow'/","",$ret);
			$ret = preg_replace("/ rel=\"nofollow\"/","",$ret);
		} else { 
  			$ret = preg_replace("/ rel='external nofollow'/"," rel='external'",$ret);
			$ret = preg_replace("/ rel=\"external nofollow\"/"," rel='external'",$ret);
  			$ret = preg_replace("/ rel='nofollow'/"," rel='external'",$ret);
			$ret = preg_replace("/ rel=\"nofollow\"/"," rel='external'",$ret);
		}

   		$ret = preg_replace("/\/thisfollow'/","' ",  $ret);
   		$ret = preg_replace("/\/thisfollow\"/","\" ",$ret);	
		$stop = '1';

	} else {

		if ($stop != '1') {

			if ( $comment->comment_approved == '1' && ( $comment->comment_type == 'pingback' || $comment->comment_type == 'trackback' ) ) {
				
				if (strpos($ret, $_SERVER['SERVER_NAME']) !== false) {
					$ret = preg_replace("/ rel='external nofollow'/","",$ret);
					$ret = preg_replace("/ rel=\"external nofollow\"/","",$ret);
					$ret = preg_replace("/ rel='nofollow'/","",$ret);
					$ret = preg_replace("/ rel=\"nofollow\"/","",$ret);

				} else {	
					$ret = preg_replace("/ rel='external nofollow'/"," rel='external'",$ret);
					$ret = preg_replace("/ rel=\"external nofollow\"/"," rel='external'",$ret);
					$ret = preg_replace("/ rel='nofollow'/"," rel='external'",$ret);
					$ret = preg_replace("/ rel=\"nofollow\"/"," rel='external'",$ret);
				}

			} else {
	
				$chars = strlen($comment->comment_content);

				if ($chars <= $nfcbc_nolink) {
					$ret = preg_replace("#<a [^>]+?>([^>]+?)</a>#i", "$1",$ret);
	
				} elseif ($chars >= $nfcbc_follow) {
					$ret = preg_replace("/ rel='external nofollow'/"," rel='external'",$ret);
					$ret = preg_replace("/ rel=\"external nofollow\"/"," rel='external'",$ret);
					$ret = preg_replace("/ rel='nofollow'/"," rel='external'",$ret);
					$ret = preg_replace("/ rel=\"nofollow\"/"," rel='external'",$ret);
				}

			}
		}

	}

	$ret = trim($ret);
	return $ret;

}

/**** HOOKS ****/
add_filter('get_comment_author_link', 'nfcbc_make_follow');
?>
<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'DM_PARTNERS'			=> 'Partners',
	'PARTNERS'				=> 'Partners',
	'DM_PARTNERS_EXPLAIN'	=> 'View our partners',
	'DMP_ADD'				=> 'Add new Partner',
	'DMP_ADDED'				=> 'Your page was added successfully. The administrator will check your request and will activate it, if everything is fine.<br />',
	'DMP_ADD_BACK_LINK'		=> '<br />Click %shere%s to restart your entry.',
	'DMP_BACK_LINK'			=> 'Click %shere%s to return to the partner page.',
	'DMP_COUNT'				=> ' Click',
	'DMP_COUNTS'			=> ' Clicks',
	'DMP_DESC'				=> 'Description of your site (*) ',
	'DMP_DESC_DESC'			=> 'Please enter a detailed description of your page. You can use BBCodes and smilies',
	'DMP_DESC_FRONT'		=> 'Here you can find our partners',
	'DMP_EMPTY_TEXT'		=> 'No description',
	'DMP_GO_ADD'			=> 'Add your site',
	'DMP_IMG'				=> 'Logo of your site ',
	'DMP_IMG_DESC'			=> 'Enter here the URL to your site logo (<strong>incl. http://</strong>).<br />The URL may not exeed the max number of 255 characters!<br />The image width should not be more than 400px.',
	'DMP_NEED'				=> 'Fields with a (*) needs to be filled!',
	'DMP_NEED_DATA'			=> 'You need to enter an <strong>URL</strong> and a <strong>title</strong>, if you like to add your site.',
	'DMP_NEED_SITE'			=> 'You need to enter a <strong>title</strong>, if you like to add your site.',
	'DMP_NEED_URL'			=> 'You need to enter an <strong>URL</strong>, if you like to add your site.',
	'DMP_NEW_ENTRY'			=> 'You have requests for new partners. Please go to your ACP and check them!',
	'DMP_NO_ENTRY'			=> 'No partners for %s',
	'DMP_SITE'				=> 'Name of your site (*) ',
	'DMP_SITE_DESC'			=> 'Enter here the name of your site. This name will be shown as title.',
	'DMP_TITLE_ADD'			=> 'Add your site',
	'DMP_TITLE_ADD_DESC'	=> 'Here you can add your own page, if you like to be listed as partner of us.<br />Please fill below form and check your entry carefully! You cannot edit the entry afterwards.',
	'DMP_URL'				=> 'URL to your site (*) ',
	'DMP_URL_DESC'			=> 'Please enter the URL to your site (<strong>incl. http://</strong>).<br />The URL may not exeed the max number of 255 characters!',
	'DMP_VIEW_NOT_ALLOWED'	=> 'Sorry, but you don\'t have the permissions to view our Partner Pages!',
	'DMP_COPYRIGHT'			=> '&copy; 2008, 2009 Die Muellers Dot Org',
	'DMP_COPYRIGHT_TITLE'	=> 'DM Partners',
	'DMP_COPYRIGHT_BASED'	=> 'Based on the',
	'DMP_COPYRIGHT2'		=> 'phpBB3 Partners Mod by djchrisnet',
	'DMP_COPYRIGHT_TITLE2'	=> 'phpBB3 Partners',
	'DMP_COPYRIGHT_DMZX'	=> '&copy; 2015 dmzx-web.net',
	'DMP_TITLE_DMZX'		=> 'dmzx-web.net',
));

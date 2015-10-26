<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(
	'ACP_DMP_CONFIG'			=> 'Configuration',
	'ACP_DMP_PARTNERS'			=> 'Partner Pages',
	'ACP_DMP_PARTNERS_DESC'		=> 'Here you can manage your partners',
	'ACP_DMP_ADDED'				=> 'Partner was successfully added',
	'ACP_DMP_AKTIV'				=> 'Partner activated',
	'ACP_DMP_AKTIV_DESC'		=> 'If you select, your partner will be shown on the Partner Page',
	'ACP_DMP_BB_SET'			=> 'Activate BBCodes',
	'ACP_DMP_COUNT'				=> ' Click',
	'ACP_DMP_COUNTS'			=> ' Clicks',
	'ACP_DMP_DELETED'			=> 'Partner was deleted successfully.',
	'ACP_DMP_DESC'				=> 'Description of the partner',
	'ACP_DMP_DESC_DESC'			=> 'Enter here a detailed description of your partner. If activated, you can use BBCodes and smilies in the descriptions of the partners.',
	'ACP_DMP_DESC_FRONT'		=> 'Here you can find all partners of your forum',
	'ACP_DMP_EMPTY_TEXT'		=> 'No description',
	'ACP_DMP_IMG'				=> 'Logo image of your partner',
	'ACP_DMP_IMG_DESC'			=> 'Enter here the URL of the logo image of your partner (incl. http://)',
	'ACP_DMP_KLICKS'			=> 'URL clicks',
	'ACP_DMP_KLICKS_AKTIV'		=> 'Will be counted',
	'ACP_DMP_NEED_DATA'			=> 'You have to enter an <strong>URL</strong> and a <strong>title</strong>, if you like to add a partner',
	'ACP_DMP_NEED_SITE'			=> 'You have to enter a <strong>title</strong>, if you like to add a partner',
	'ACP_DMP_NEED_URL'			=> 'You have to enter an <strong>URL</strong>, if you like to add a partner',
	'ACP_DMP_NO_ENTRY'			=> 'There are no partners listed',
	'ACP_DMP_REALY_DELETE'		=> 'Are you sure you like to delete this partner?',
	'ACP_DMP_SAVE_KLICK'		=> 'Click counter activated',
	'ACP_DMP_SAVE_KLICK_DESC'	=> 'If this option is selected, the click counter is activated and will be shown. You may enter a start value in the filed beside',
	'ACP_DMP_SETTING'			=> 'Text settings',
	'ACP_DMP_SETTING_DESC'		=> 'Select, if you like to use BBCodes, smilies and/or URL links',
	'ACP_DMP_SITE'				=> 'Site name of your partner',
	'ACP_DMP_SITE_DESC'			=> 'Enter here the name or sitename of your partner. This will be shown as clickable title',
	'ACP_DMP_SMILY_SET'			=> 'Activate smilies',
	'ACP_DMP_TITLE_ADD'			=> 'Add partner',
	'ACP_DMP_TITLE_EDIT'		=> 'Edit partner',
	'ACP_DMP_UPDATED'			=> 'Partner was modified successfully',
	'ACP_DMP_URL'				=> 'URL',
	'ACP_DMP_URL_DESC'			=> 'Enter here the URL to the site of your partner (incl. http://)',
	'ACP_DMP_URL_SET'			=> 'Active URL usage',
	'ACP_DMP_EDIT_EXPLAIN'		=> 'Here you can edit an existing partner',
	'ACP_DMP_ADD_EXPLAIN'		=> 'Here you can add a new partner',

	// Log entires
	'LOG_DMP_DELETE'			=> 'Deleted partner <strong># %s</strong>',
	'LOG_DMP_SAVE'				=> 'Edited partner <strong>%s</strong>',
	'LOG_DMP_SAVE_NEW'			=> 'Added new partner <strong>%s</strong>',

	// Permissions
	'ACL_U_DM_PARTNERS_ADD'		=> 'Can add partners to Partner Page',
	'ACL_U_DM_PARTNERS_VIEW'	=> 'Can view Partner Page',
));

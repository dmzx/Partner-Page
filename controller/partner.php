<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\controller;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;

class partner
{
	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var auth */
	protected $auth;

	/** @var driver_interface */
	protected $db;

	/** @var request */
	protected $request;

	/** @var config */
	protected $config;

	/** @var helper */
	protected $helper;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $phpEx;

	/** @var string */
	protected $dm_partners_table;

	/**
	* Constructor
	*
	* @param template		 	$template
	* @param user						$user
	* @param auth					$auth
	* @param driver_interface	$db
	* @param request		 		$request
	* @param config				$config
	* @param helper		 	$helper
	* @param									$phpbb_root_path
	* @param									$phpEx
	* @param									$dm_partners_table
	*
	*/
	public function __construct(
		template $template,
		user $user,
		auth $auth,
		driver_interface $db,
		request $request,
		config $config,
		helper $helper,
		$phpbb_root_path,
		$phpEx,
		$dm_partners_table
	)
	{
		$this->template 			= $template;
		$this->user 				= $user;
		$this->auth 				= $auth;
		$this->db 					= $db;
		$this->request 				= $request;
		$this->config 				= $config;
		$this->helper 				= $helper;
		$this->phpbb_root_path 		= $phpbb_root_path;
		$this->phpEx 				= $phpEx;
		$this->dm_partners_table 	= $dm_partners_table;
	}

	public function handle_partner()
	{
		include($this->phpbb_root_path . 'includes/functions_user.' .$this->phpEx);
		include($this->phpbb_root_path . 'includes/bbcode.' . $this->phpEx);

		$mode = $this->request->variable('mode', 'view', 'add');
		$post = $this->request->get_super_global(request::POST);
		$get = $this->request->get_super_global(request::GET);

		if (!$this->auth->acl_get('u_dm_partners_view'))
		{
			$message = $this->user->lang['NOT_AUTHORISED'];
			trigger_error($message);
		}

		$sql = 'SELECT MAX(right_id) AS right_id
				FROM ' . $this->dm_partners_table;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($mode == 'add')
		{
			if (($this->user->data['is_bot']) || ($this->user->data['user_id'] == ANONYMOUS))
			{
				redirect(append_sid("{$this->phpbb_root_path}ucp.$this->phpEx?mode=login"));
			}

			if (isset($post['submit']))
			{
				$back_link = sprintf($this->user->lang['DMP_ADD_BACK_LINK'], "<a href=\"" .$this->helper->route('dmzx_partner_controller', ['mode' => 'add']) . "\">", "</a>");
				$lang_mode = $this->user->lang['DMP_TITLE_ADD'];
				$action = (!isset($get['action'])) ? '' : $get['action'];
				$action = ((isset($post['submit']) && !$post['id']) ? 'add' : $action );
				$id = $this->request->variable('id', 0);
				$site = $this->request->variable('site', '', true);
				$url = $this->request->variable('url', '');
				$creator_id = $this->user->data['user_id'];
				$text = $this->request->variable('text', '', true);

				$this->template->assign_vars([
					'S_POST_ACTION'			=> $this->helper->route('dmzx_partner_controller', ['mode' => 'add']),
                ]);

				if ($site == '' || $url == '' || $text == '')
				{
					if ($site == '' && $url == '' && $text == '')
					{
						trigger_error($this->user->lang['DMP_NEED_DATA']. $back_link);
					}
					else if ($site == '')
					{
						trigger_error($this->user->lang['DMP_NEED_SITE'] . $back_link);
					}
					else if ($url == '')
					{
						trigger_error($this->user->lang['DMP_NEED_URL'] . $back_link);
					}
					else if ($text == '')
					{
						trigger_error($this->user->lang['DMP_EMPTY_TEXT'] . $back_link);
					}
				}
				else
				{
					$uid = $bitfield = $options = '';
					$allow_bbcode = $allow_urls = $allow_smilies = true;
					generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

					//Make SQL Array
					$sql_ary = [
						'title'				=> $site,
						'left_id' 			=> $row['right_id'] + 1,
						'right_id' 			=> $row['right_id'] + 2,
						'text'				=> $text,
						'bbcode_uid'		=> $uid,
						'bbcode_bitfield'	=> $bitfield,
						'enable_bbcode'		=> $allow_bbcode,
						'enable_smilies'	=> $allow_smilies,
						'enable_magic_url'	=> $allow_urls,
						'enable_count'		=> $this->request->variable('click_set', 1),
						'url'				=> $url,
						'image_url'			=> $this->request->variable('image', ''),
						'bg_url'			=> $this->request->variable('bg_image', ''),
						'counter'			=> $this->request->variable('clicks', 1),
						'activ'				=> $this->request->variable('activ', 0),
						'creator_id'		=> $creator_id,
                    ];

					$this->db->sql_query('INSERT INTO ' . $this->dm_partners_table .' ' . $this->db->sql_build_array('INSERT', $sql_ary));
					trigger_error($this->user->lang['DMP_ADDED'] . sprintf($this->user->lang['DMP_BACK_LINK'], "<a href=\"" . $this->helper->route('dmzx_partner_controller') . "\">", "</a>"));
				}
			}
		}

		switch($mode)
		{
			case 'redirect':

				$id = $this->request->variable('id', 0);

				$result = $this->db->sql_query('SELECT url FROM ' . $this->dm_partners_table . ' WHERE id = ' . (int) $id);
				$row = $this->db->sql_fetchrow($result);
				$this->db->sql_query('UPDATE ' . $this->dm_partners_table . ' SET counter = counter + 1 WHERE id = ' . (int) $id);

				header("Status: 301 Permanently Moved");
				header("Location: {$row['url']}");
			exit;
			break;

			case 'view':
				//Catch data from DB but only active partners
				$sql_array = [
					'SELECT'	=> '*',
					'FROM'		=> [
				$this->dm_partners_table => 'p',
                    ],
					'WHERE'		=> 'activ <> 0',
					'ORDER_BY'	=> 'left_id ASC',
                ];
				$sql = $this->db->sql_build_query('SELECT', $sql_array);
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$allow_bbcode = $row['enable_bbcode'];
					$allow_smilies = $row['enable_smilies'];
					$allow_urls = $row['enable_magic_url'];
					$option = (($allow_bbcode) ? OPTION_FLAG_BBCODE : 0) + (($allow_smilies) ? OPTION_FLAG_SMILIES : 0) + (($allow_urls) ? OPTION_FLAG_LINKS : 0);

					$image = [];
					if ($row['text'] == '')
					{
						$row['text'] = $this->user->lang['DMP_EMPTY_TEXT'];
					}

					$this->template->assign_block_vars('partners', [
						'S_IMAGE'	=> ($row['image_url'] !== '') ? true : false,
						'S_COUNT'	=> ($row['enable_count'] == 1) ? true : false,
						'IMAGE'		=> $row['image_url'],
						'BG_IMAGE'	=> $row['bg_url'],
						'URL'		=> ($row['enable_count'] == 1) ? $this->helper->route('dmzx_partner_controller', ['mode' => 'redirect' ,'id' => $row['id']]): $row['url'],
						'TITLE'		=> censor_text($row['title']),
						'TEXT'		=> generate_text_for_display($row['text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $option),
						'COUNTER'	=> ($row['counter'] == 1) ? $row['counter'].$this->user->lang['DMP_COUNT'] : number_format($row['counter'], 0, '.', '.').$this->user->lang['DMP_COUNTS'],
						'S_ROW_ID'	=> (int) $row['id'],
                    ]);
				}

				if (!$row)
				{
					$this->template->assign_vars([
						'NO_ENTRY'	=> sprintf($this->user->lang['DMP_NO_ENTRY'], $this->config['sitename']),
                    ]);
				}

				$this->db->sql_freeresult($result);

			break;
		}

		$this->template->assign_vars([
			'ADD_IMG'		=> $this->user->img('button_partners', 'DMP_ADD'),
			'U_ADD_IMG'		=> $this->helper->route('dmzx_partner_controller', ['mode' => 'add']),
			'S_ALLOW_ADD'	=> $this->auth->acl_get('u_dm_partners_add'),
			'S_ALLOW_VIEW'	=> ($this->auth->acl_get('u_dm_partners_view')) ? true : false,
        ]);

		page_header($this->user->lang['PARTNERS']);

		$this->template->set_filenames([
			'body' => ($mode == 'add') ? 'dm_partners_add_body.html' : 'dm_partners_body.html']
		);

		page_footer();
	}
}

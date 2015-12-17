<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\controller;

class partner
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
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
	* @param \phpbb\template\template		 	$template
	* @param \phpbb\user						$user
	* @param \phpbb\auth\auth					$auth
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\request\request		 		$request
	* @param \phpbb\config\config				$config
	* @param \phpbb\controller\helper		 	$helper
	* @param									$phpbb_root_path
	* @param									$phpEx
	* @param									$dm_partners_table
	*
	*/

	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\config\config $config, \phpbb\controller\helper $helper, $phpbb_root_path, $phpEx, $dm_partners_table)
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
		$post = $this->request->get_super_global(\phpbb\request\request::POST);
		$get = $this->request->get_super_global(\phpbb\request\request::GET);

		if ( !$this->auth->acl_get('u_dm_partners_view') )
		{
			$message = $this->user->lang['NOT_AUTHORISED'];
				trigger_error($message);
		}

		if ( $mode == 'add' )
		{
			if (($this->user->data['is_bot']) || ($this->user->data['user_id'] == ANONYMOUS))
			{
				redirect(append_sid("{$this->phpbb_root_path}ucp.$this->phpEx?mode=login"));
			}

			if ( isset($post['submit']) )
			{
				$back_link = sprintf($this->user->lang['DMP_ADD_BACK_LINK'], "<a href=\"" .$this->helper->route('dmzx_partner_controller', array('mode' => 'add')) . "\">", "</a>");
				$lang_mode = $this->user->lang['DMP_TITLE_ADD'];
				$action = (!isset($get['action'])) ? '' : $get['action'];
				$action = ((isset($post['submit']) && !$post['id']) ? 'add' : $action );
				$id = $this->request->variable('id', 0);
				$site = $this->request->variable('site', '', true);
				$url = $this->request->variable('url', '');
				$creator_id = $this->user->data['user_id'];

				//Settings
				$text = $this->request->variable('text', '', true);
				$uid = $bitfield = $options = '';
				$allow_bbcode = $allow_urls = $allow_smilies = true;
				generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

				//Make SQL Array
				$sql_ary = array(
					'title'				=> $site,
					'text'				=> $text,
					'bbcode_uid'		=> $uid,
					'bbcode_bitfield'	=> $bitfield,
					'enable_bbcode'		=> $allow_bbcode,
					'enable_smilies'	=> $allow_smilies,
					'enable_magic_url'	=> $allow_urls,
					'enable_count'		=> $this->request->variable('click_set', 1),
					'url'				=> $url,
					'image_url'			=> $this->request->variable('image', ''),
					'counter'			=> $this->request->variable('clicks', 1),
					'activ'				=> $this->request->variable('activ', 0),
					'creator_id'		=> $creator_id,
				);

				$this->template->assign_vars(array(
					'S_POST_ACTION'			=> $this->helper->route('dmzx_partner_controller', array('mode' => 'add')),
				));

				if ($site == '' || $url == '')
				{
					if ($site == '' && $url == '')
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
				}
				else
				{
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
				//Catch data from DB but only activ partners
				$sql_array = array(
					'SELECT'	=> '*',
					'FROM'		=> array(
				$this->dm_partners_table => 'p',
				),
					'WHERE'		=> 'activ <> 0',
					'ORDER_BY'	=> 'title ASC',
				);
				$sql = $this->db->sql_build_query('SELECT', $sql_array);
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$allow_bbcode = $row['enable_bbcode'];
					$allow_smilies = $row['enable_smilies'];
					$allow_urls = $row['enable_magic_url'];
					$option = (($allow_bbcode) ? OPTION_FLAG_BBCODE : 0) + (($allow_smilies) ? OPTION_FLAG_SMILIES : 0) + (($allow_urls) ? OPTION_FLAG_LINKS : 0);

					$image = array();
					if ($row['text'] == '')
					{
						$row['text'] = $this->user->lang['DMP_EMPTY_TEXT'];
					}

					$this->template->assign_block_vars('partners', array(
						'S_IMAGE'	=> ($row['image_url'] !== '') ? true : false,
						'S_COUNT'	=> ($row['enable_count'] == 1) ? true : false,
						'IMAGE'		=> $row['image_url'],
						'URL'		=> ($row['enable_count'] == 1) ? $this->helper->route('dmzx_partner_controller', array('mode' => 'redirect' ,'id' => $row['id'])): $row['url'],
						'TITLE'		=> censor_text($row['title']),
						'TEXT'		=> generate_text_for_display($row['text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $option),
						'COUNTER'	=> ($row['counter'] == 1) ? $row['counter'].$this->user->lang['DMP_COUNT'] : number_format($row['counter'], 0, '.', '.').$this->user->lang['DMP_COUNTS'],
						)
					);
				}

				if (!$row)
				{
					$this->template->assign_vars(array(
						'NO_ENTRY'	=> sprintf($this->user->lang['DMP_NO_ENTRY'], $this->config['sitename']),
					)
					);
				}

				$this->db->sql_freeresult($result);

			break;
		}

		$this->template->assign_vars(array(
			'ADD_IMG'		=> $this->user->img('button_partners', 'DMP_ADD'),
			'U_ADD_IMG'		=> $this->helper->route('dmzx_partner_controller', array('mode' => 'add')),
			'S_ALLOW_ADD'	=> $this->auth->acl_get('u_dm_partners_add'),
			'S_ALLOW_VIEW'	=> ($this->auth->acl_get('u_dm_partners_view')) ? true : false,
			)
		);

		page_header($this->user->lang['PARTNERS']);

		$this->template->set_filenames(array(
			'body' => ($mode == 'add') ? 'dm_partners_add_body.html' : 'dm_partners_body.html')
		);

		page_footer();
	}
}

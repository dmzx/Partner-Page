<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\acp;

use phpbb\json_response;

class partner_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $phpbb_container, $user, $template, $request, $config;

		$dm_partners_table = $phpbb_container->getParameter('dmzx.partner.table');

		$user->add_lang_ext('dmzx/partner', 'acp_partner');

		$form_action = $this->u_action . '&amp;action=add';
		$lang_mode = $user->lang['ACP_DMP_TITLE_ADD'];
		$id = $request->variable('id', 0);
		$site = $request->variable('site', '', true);
		$url = $request->variable('url', '');
		$creator_id = $user->data['user_id'];
		$action = $request->variable('action', '');
		$action = (isset($_POST['add'])) ? 'add' : ((isset($_POST['save'])) ? 'save' : $action);
		$text = $request->variable('text', '', true);
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

		$sql = 'SELECT MAX(right_id) AS right_id
				FROM ' . $dm_partners_table;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		switch ($action)
		{
			// Add new partner
			case 'add':

				$sql_array = [
					'SELECT'	=> '*',
					'FROM'		=> [
						$dm_partners_table => 'p',
                    ],
					'WHERE'		=> 'id = ' . $id,
                ];
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query_limit($sql, 1);
				$row = $db->sql_fetchrow($result);

				$template->assign_vars([
					'S_ADD'				=> true,
					'ID'				=> isset($row['id']) ? $row['id'] : '',
					'SITE'				=> isset($row['title']) ? $row['title'] : '',
					'TEXT'				=> isset($row['text']) ? $row['text'] : '',
					'ACTIV'				=> (isset($row['activ']) == '1') ? 'checked="checked"' : '',
					'BB_SET'			=> (isset($row['enable_bbcode']) == '1') ? 'checked="checked"' : '',
					'SMILIE_SET'		=> (isset($row['enable_smilies']) == '1') ? 'checked="checked"' : '',
					'URL_SET'			=> (isset($row['enable_magic_url']) == '1') ? 'checked="checked"' : '',
					'URL'				=> isset($row['url']) ? $row['url'] : '',
					'IMG'				=> isset($row['image_url']) ? $row['image_url'] : '',
					'BG_IMG'			=> isset($row['bg_url']) ? $row['bg_url'] : '',
					'CLICK_SET'			=> (isset($row['enable_count']) == '1') ? 'checked="checked"' : '',
					'CLICKS'			=> isset($row['counter']) ? $row['counter'] : '',
					'MODE_TITLE'		=> $user->lang['ACP_DMP_TITLE_ADD'],
					'U_BACK'			=> $this->u_action,
                ]);
			break;

			case 'save':
				$partner_id = $request->variable('id', 0);

				if ($site == '' || $url == '')
				{
					if ($site == '' && $url == '')
					{
						trigger_error($user->lang['ACP_DMP_NEED_DATA'] . adm_back_link($this->u_action));
					}
					else if ($site == '')
					{
						trigger_error($user->lang['ACP_DMP_NEED_SITE'] . adm_back_link($this->u_action));
					}
					else if ($url == '')
					{
						trigger_error($user->lang['ACP_DMP_NEED_URL'] . adm_back_link($this->u_action));
					}
				}

				if ($partner_id)
				{
					//Update SQL Array
					$sql_ary = [
						'title'				=> $site,
						'text'				=> $text,
						'bbcode_uid'		=> $uid,
						'bbcode_bitfield'	=> $bitfield,
						'enable_bbcode'		=> $request->variable('bb_set', 0),
						'enable_smilies'	=> $request->variable('smilie_set', 0),
						'enable_magic_url'	=> $request->variable('url_set', 0),
						'enable_count'		=> $request->variable('click_set', 0),
						'url'				=> $url,
						'image_url'			=> $request->variable('image', ''),
						'bg_url'			=> $request->variable('bg_image', ''),
						'counter'			=> $request->variable('clicks', 0),
						'activ'				=> $request->variable('activ', 0),
						'creator_id'		=> $creator_id,
                    ];

					$db->sql_query('UPDATE ' . $dm_partners_table . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . ' WHERE ID = ' . $id);

					add_log('admin', 'LOG_DMP_SAVE', str_replace('%', '*', $site));

					trigger_error($user->lang['ACP_DMP_UPDATED'] . adm_back_link($this->u_action));
				}
				else
				{
					//Insert SQL Array
					$sql_ary = [
						'title'				=> $site,
						'left_id' 			=> $row['right_id'] + 1,
						'right_id' 			=> $row['right_id'] + 2,
						'text'				=> $text,
						'bbcode_uid'		=> $uid,
						'bbcode_bitfield'	=> $bitfield,
						'enable_bbcode'		=> $request->variable('bb_set', 0),
						'enable_smilies'	=> $request->variable('smilie_set', 0),
						'enable_magic_url'	=> $request->variable('url_set', 0),
						'enable_count'		=> $request->variable('click_set', 0),
						'url'				=> $url,
						'image_url'			=> $request->variable('image', ''),
						'bg_url'			=> $request->variable('bg_image', ''),
						'counter'			=> $request->variable('clicks', 0),
						'activ'				=> $request->variable('activ', 0),
						'creator_id'		=> $creator_id,
                    ];

					$db->sql_query('INSERT INTO ' . $dm_partners_table .' ' . $db->sql_build_array('INSERT', $sql_ary));

					add_log('admin', 'LOG_DMP_SAVE_NEW', str_replace('%', '*', $site));

					trigger_error($user->lang['ACP_DMP_ADDED'] . adm_back_link($this->u_action));
				}
			break;

			case 'move_down':
			case 'move_up':
			$partner_id = $request->variable('id', 0);
				$this->partner_category_move($partner_id, $action);
			break;

			// Edit partner
			case 'edit':
				$form_action = $this->u_action. '&amp;action=update';
				$lang_mode = $user->lang['ACP_DMP_TITLE_EDIT'];

				$sql_array = [
					'SELECT'	=> '*',
					'FROM'		=> [
						$dm_partners_table => 'p',
                    ],
					'WHERE'		=> 'id = ' . $id,
                ];
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query_limit($sql, 1);
				$row = $db->sql_fetchrow($result);
				decode_message($row['text'], $row['bbcode_uid']);

				$template->assign_vars([
					'S_MAIN'		=> false,
					'S_EDIT'		=> true,
					'ID'			=> $row['id'],
					'SITE'			=> $row['title'],
					'TEXT'			=> $row['text'],
					'ACTIV'			=> ($row['activ'] == '1') ? 'checked="checked"' : '',
					'BB_SET'		=> ($row['enable_bbcode'] == '1') ? 'checked="checked"' : '',
					'SMILIE_SET'	=> ($row['enable_smilies'] == '1') ? 'checked="checked"' : '',
					'URL_SET'		=> ($row['enable_magic_url'] == '1') ? 'checked="checked"' : '',
					'URL'			=> $row['url'],
					'IMG'			=> $row['image_url'],
					'BG_IMG'		=> $row['bg_url'],
					'CLICK_SET'		=> ($row['enable_count'] == '1') ? 'checked="checked"' : '',
					'CLICKS'		=> $row['counter'],
					'MODE_TITLE'	=> $user->lang['ACP_DMP_TITLE_EDIT'],
					'U_BACK'		=> $this->u_action,
                ]);
			break;

			// Delete partner
			case 'delete':
				if (confirm_box(true))
				{
					$sql = 'SELECT title
						FROM ' . $dm_partners_table . "
						WHERE id = $id";
					$result = $db->sql_query($sql);
					$site = $db->sql_fetchfield('title');
					$db->sql_freeresult($result);

					$sql = 'DELETE FROM ' . $dm_partners_table . "
						WHERE id = $id";
					$db->sql_query($sql);

					add_log('admin', 'LOG_DMP_DELETE', str_replace('%', '*', $site));

					trigger_error($user->lang['ACP_DMP_DELETED'] . adm_back_link($this->u_action));
				}
				else
				{
					confirm_box(false, sprintf($user->lang['ACP_DMP_REALY_DELETE'], $site), build_hidden_fields([
						'id'			=> $id,
						'action'	=> 'delete',
                        ])
					);
				}
			break;
		}

		// List all partners
		$sql_array = [
			'SELECT'	=> '*',
			'FROM'		=> [
				$dm_partners_table => 'p',
            ],
			'ORDER_BY'	=> 'left_id',
        ];
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('partners', [
				'TITLE'			=> $row['title'],
				'URL'			=> $row['url'],
				'IMG'			=> ($row['image_url'] !== '') ? $user->lang['YES'] : $user->lang['NO'],
				'BG_IMG'		=> ($row['bg_url'] !== '') ? $user->lang['YES'] : $user->lang['NO'],
				'CLICKS'		=> $row['counter'],
				'CLICKS_EN'		=> ($row['enable_count'] == 1) ? $user->lang['YES'] : $user->lang['NO'],
				'ACTIV'			=> ($row['activ']) ? $user->lang['YES'] : $user->lang['NO'],
				'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' .$row['id'],
				'U_DEL'			=> $this->u_action . '&amp;action=delete&amp;id=' .$row['id'],
				'U_MOVE_DOWN'	=> $this->u_action . '&amp;action=move_down&amp;id=' . (int) $row['id'] . '&amp;hash=' . generate_link_hash('partner_move'),
				'U_MOVE_UP'		=> $this->u_action . '&amp;action=move_up&amp;id=' . (int) $row['id'] . '&amp;hash=' . generate_link_hash('partner_move'),
            ]);
		}
		$db->sql_freeresult($result);

		$template->assign_vars([
			'S_MAIN'				=> true,
			'U_ACTION'				=> $form_action,
			'ACP_PARTNER_VERSION'	=> $config['partner_version'],
        ]);

		$this->tpl_name = 'acp_dm_partners';
		$this->page_title = 'ACP_DMP_PARTNERS';
	}

	public function partner_category_move($partner_id, $direction)
	{
		global $db, $phpbb_container, $user, $request;

		$dm_partners_table = $phpbb_container->getParameter('dmzx.partner.table');

		# Check hash for security
		if (!check_link_hash($request->variable('hash', ''), 'partner_move'))
		{
			trigger_error($user->lang['ACP_DMP_NOHASH'] . adm_back_link($this->u_action), E_USER_WARNING);
		}

		$sql = 'SELECT *
				FROM ' . $dm_partners_table . '
				WHERE id = ' . (int) $partner_id;
		$result = $db->sql_query($sql);
		$item = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$move_cat_name = $this->partner_category_move_by($item, $direction);

		if ($request->is_ajax())
		{
			$json_response = new json_response;
			$json_response->send(['success' => ($move_cat_name !== false)]);
		}
	}

	private function partner_category_move_by($cat_row, $direction = 'move_up', $steps = 1)
	{
		global $db, $phpbb_container;

		$dm_partners_table = $phpbb_container->getParameter('dmzx.partner.table');

		$sql = 'SELECT id, title, left_id, right_id
			FROM ' . $dm_partners_table . "
			WHERE " . (($direction == 'move_up') ? "right_id < {$cat_row['right_id']} ORDER BY right_id DESC" : "left_id > {$cat_row['left_id']} ORDER BY left_id ASC");
		$result = $db->sql_query_limit($sql, $steps);
		$target = [];
		while ($row = $db->sql_fetchrow($result))
		{
			$target = $row;
		}
		$db->sql_freeresult($result);
		if (!sizeof($target))
		{
			// The category is already on top or bottom
			return false;
		}

		if ($direction == 'move_up')
		{
			$left_id = $target['left_id'];
			$right_id = $cat_row['right_id'];
			$diff_up = $cat_row['left_id'] - $target['left_id'];
			$diff_down = $cat_row['right_id'] + 1 - $cat_row['left_id'];
			$move_up_left = $cat_row['left_id'];
			$move_up_right = $cat_row['right_id'];
		}
		else
		{
			$left_id = $cat_row['left_id'];
			$right_id = $target['right_id'];
			$diff_up = $cat_row['right_id'] + 1 - $cat_row['left_id'];
			$diff_down = $target['right_id'] - $cat_row['right_id'];
			$move_up_left = $cat_row['right_id'] + 1;
			$move_up_right = $target['right_id'];
		}

		$sql = 'UPDATE ' . $dm_partners_table . "
			SET left_id = left_id + CASE
				WHEN left_id BETWEEN {$move_up_left} AND {$move_up_right} THEN -{$diff_up}
				ELSE {$diff_down}
			END,
			right_id = right_id + CASE
				WHEN right_id BETWEEN {$move_up_left} AND {$move_up_right} THEN -{$diff_up}
				ELSE {$diff_down}
			END
			WHERE
				left_id BETWEEN {$left_id} AND {$right_id}
				AND right_id BETWEEN {$left_id} AND {$right_id}";
		$db->sql_query($sql);

		return $target['title'];
	}
}

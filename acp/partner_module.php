<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\acp;

class partner_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $phpbb_container, $user, $template, $phpbb_container, $request;

		$dm_partners_table = $phpbb_container->getParameter('dmzx.partner.table');

		$form_action = $this->u_action. '&amp;action=add';
		$lang_mode = $user->lang['ACP_DMP_TITLE_ADD'];
		$id = $request->variable('id', 0);
		$site = $request->variable('site', '', true);
		$url = $request->variable('url', '');
		$creator_id = $user->data['user_id'];
		$action = $request->variable('action', '');
		$action = (isset($_POST['add'])) ? 'add' : ((isset($_POST['save'])) ? 'save' : $action);

		//Settings
		$text = utf8_normalize_nfc($request->variable('text', '', true));
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

		//Make SQL Array
		$sql_ary = array(
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
			'counter'			=> $request->variable('clicks', 0),
			'activ'				=> $request->variable('activ', 0),
			'creator_id'		=> $creator_id,
		);

		switch ($action)
		{
			// Add new partner
			case 'add':

			$sql_array = array(
					'SELECT'	=> '*',
					'FROM'		=> array(
						$dm_partners_table => 'p',
					),
					'WHERE'		=> 'id = ' . $id,
				);
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query_limit($sql, 1);
				$row = $db->sql_fetchrow($result);

				$template->assign_vars(array(
					'S_ADD'				=> true,
					'ID'				=> $row['id'],
					'SITE'				=> $row['title'],
					'TEXT'				=> $row['text'],
					'ACTIV'				=> ($row['activ'] == '1') ? 'checked="checked"' : '',
					'BB_SET'			=> ($row['enable_bbcode'] == '1') ? 'checked="checked"' : '',
					'SMILIE_SET'		=> ($row['enable_smilies'] == '1') ? 'checked="checked"' : '',
					'URL_SET'			=> ($row['enable_magic_url'] == '1') ? 'checked="checked"' : '',
					'URL'				=> $row['url'],
					'IMG'				=> $row['image_url'],
					'CLICK_SET'			=> ($row['enable_count'] == '1') ? 'checked="checked"' : '',
					'CLICKS'			=> $row['counter'],
					'MODE_TITLE'		=> $user->lang['ACP_DMP_TITLE_ADD'],
					'U_BACK'			=> $this->u_action,
				));
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
					$db->sql_query('UPDATE ' . $dm_partners_table . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . ' WHERE ID = ' . $id);

					add_log('admin', 'LOG_DMP_SAVE', str_replace('%', '*', $site));

					trigger_error($user->lang['ACP_DMP_UPDATED'] . adm_back_link($this->u_action));
				}
				else
				{
					$db->sql_query('INSERT INTO ' . $dm_partners_table .' ' . $db->sql_build_array('INSERT', $sql_ary));

					add_log('admin', 'LOG_DMP_SAVE_NEW', str_replace('%', '*', $site));

					trigger_error($user->lang['ACP_DMP_ADDED'] . adm_back_link($this->u_action));
				}
			break;

			// Edit partner
			case 'edit':
				$form_action = $this->u_action. '&amp;action=update';
				$lang_mode = $user->lang['ACP_DMP_TITLE_EDIT'];

				$sql_array = array(
					'SELECT'	=> '*',
					'FROM'		=> array(
						$dm_partners_table => 'p',
					),
					'WHERE'		=> 'id = ' . $id,
				);
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query_limit($sql, 1);
				$row = $db->sql_fetchrow($result);
				decode_message($row['text'], $row['bbcode_uid']);

				$template->assign_vars(array(
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
					'CLICK_SET'		=> ($row['enable_count'] == '1') ? 'checked="checked"' : '',
					'CLICKS'		=> $row['counter'],
					'MODE_TITLE'	=> $user->lang['ACP_DMP_TITLE_EDIT'],
					'U_BACK'		=> $this->u_action,
				));
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
					confirm_box(false, sprintf($user->lang['ACP_DMP_REALY_DELETE'], $site), build_hidden_fields(array(
						'id'			=> $id,
						'action'	=> 'delete',
						))
					);
				}
			break;
		}

		// List all partners
		$sql_array = array(
			'SELECT'	=> '*',
			'FROM'		=> array(
				$dm_partners_table => 'p',
			),
			'ORDER_BY'	=> 'id',
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('partners', array(
				'TITLE'			=> $row['title'],
				'URL'			=> $row['url'],
				'IMG'			=> ($row['image_url'] !== '') ? $user->lang['YES'] : $user->lang['NO'],
				'CLICKS'		=> $row['counter'],
				'CLICKS_EN'		=> ($row['enable_count'] == 1) ? $user->lang['YES'] : $user->lang['NO'],
				'ACTIV'			=> ($row['activ']) ? $user->lang['YES'] : $user->lang['NO'],
				'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' .$row['id'],
				'U_DEL'			=> $this->u_action . '&amp;action=delete&amp;id=' .$row['id'],
			));
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'S_MAIN'		=> true,
			'U_ACTION'		=> $form_action,
		));

		$this->tpl_name = 'acp_dm_partners';
		$this->page_title = 'ACP_DMP_PARTNERS';
	}
}

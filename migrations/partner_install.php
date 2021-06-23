<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

use phpbb\db\migration\migration;

class partner_install extends migration
{
	public function effectively_installed()
	{
		return isset($this->config['partner_version']) && version_compare($this->config['partner_version'], '1.0.2', '>=');
	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320a1'];
	}

	public function update_data()
	{
		return [

			// Add configs
			['config.add', ['dm_partners_new', 1]],
			['config.add', ['partner_version', '1.0.2']],

			// Add permissions
			['permission.add', ['a_dm_partners_edit', true]],
			['permission.add', ['u_dm_partners_view', true]],
			['permission.add', ['u_dm_partners_add', true]],

			// Set permissions
			['permission.permission_set', ['REGISTERED', 'u_dm_partners_view', 'group']],
			['permission.permission_set', ['ADMINISTRATORS', 'a_dm_partners_edit', 'group']],
			['permission.permission_set', ['ADMINISTRATORS', 'u_dm_partners_view', 'group']],
			['permission.permission_set', ['ADMINISTRATORS', 'u_dm_partners_add', 'group']],

			// Add ACP module
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_DMP_PARTNERS'
            ]],
			['module.add', [
				'acp',
				'ACP_DMP_PARTNERS',
				[
					'module_basename'	=> '\dmzx\partner\acp\partner_module',
					'modes' => ['acp_dmp_config'],
                ],
            ]],
        ];
	}

	public function update_schema()
	{
		return [
			'add_tables'	=> [
				$this->table_prefix . 'dm_partners'	=> [
					'COLUMNS'	=> [
						'id'				=> ['UINT:10', null, 'auto_increment'],
						'creator_id'		=> ['UINT:10', 0],
						'title'				=> ['MTEXT_UNI', ''],
						'url'				=> ['VCHAR', ''],
						'image_url'			=> ['VCHAR', ''],
						'bg_url'			=> ['VCHAR', ''],
						'counter'			=> ['UINT:10', 0],
						'text'				=> ['MTEXT_UNI', ''],
						'bbcode_bitfield'	=> ['VCHAR', ''],
						'bbcode_uid'		=> ['VCHAR:8', ''],
						'enable_bbcode'		=> ['BOOL', 0],
						'enable_smilies'	=> ['BOOL', 0],
						'enable_magic_url'	=> ['BOOL', 0],
						'enable_count'		=> ['BOOL', 0],
						'activ'				=> ['BOOL', 0],
                    ],
					'PRIMARY_KEY'	=> 'id',
                ],
            ],
        ];
	}

	public function revert_schema()
	{
		return 	[
			'drop_tables' => [
				$this->table_prefix . 'dm_partners',
            ],
        ];
	}
}

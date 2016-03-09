<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

class partner_install extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['partner_version']) && version_compare($this->config['partner_version'], '1.0.2', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320a1');
	}

	public function update_data()
	{
		return array(

			// Add configs
			array('config.add', array('dm_partners_new', 1)),
			array('config.add', array('partner_version', '1.0.2')),

			// Add permissions
			array('permission.add', array('a_dm_partners_edit', true)),
			array('permission.add', array('u_dm_partners_view', true)),
			array('permission.add', array('u_dm_partners_add', true)),

			// Set permissions
			array('permission.permission_set', array('REGISTERED', 'u_dm_partners_view', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'a_dm_partners_edit', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dm_partners_view', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dm_partners_add', 'group')),

			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_DMP_PARTNERS'
			)),
			array('module.add', array(
				'acp',
				'ACP_DMP_PARTNERS',
				array(
					'module_basename'	=> '\dmzx\partner\acp\partner_module',
					'modes' => array('acp_dmp_config'),
				),
			)),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'dm_partners'	=> array(
					'COLUMNS'	=> array(
						'id'				=> array('UINT:10', null, 'auto_increment'),
						'creator_id'		=> array('UINT:10', 0),
						'title'				=> array('MTEXT_UNI', ''),
						'url'				=> array('VCHAR', ''),
						'image_url'			=> array('VCHAR', ''),
						'bg_url'			=> array('VCHAR', ''),
						'counter'			=> array('UINT:10', 0),
						'text'				=> array('MTEXT_UNI', ''),
						'bbcode_bitfield'	=> array('VCHAR', ''),
						'bbcode_uid'		=> array('VCHAR:8', ''),
						'enable_bbcode'		=> array('BOOL', 0),
						'enable_smilies'	=> array('BOOL', 0),
						'enable_magic_url'	=> array('BOOL', 0),
						'enable_count'		=> array('BOOL', 0),
						'activ'				=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),
		);
	}

	public function revert_schema()
	{
		return 	array(
			'drop_tables' => array(
				$this->table_prefix . 'dm_partners',
			),
		);
	}
}

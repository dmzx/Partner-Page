<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

class partner_schema extends \phpbb\db\migration\migration
{
	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'dm_partners'	=> array(
					'COLUMNS'	=> array(
						'id'				=> array('UINT:10', NULL, 'auto_increment'),
						'creator_id'		=> array('UINT:10', 0),
						'title'				=> array('MTEXT_UNI', ''),
						'url'				=> array('VCHAR', ''),
						'image_url'			=> array('VCHAR', ''),
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

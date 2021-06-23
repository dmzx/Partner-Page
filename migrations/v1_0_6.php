<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2021 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

use phpbb\db\migration\migration;

class v1_0_6 extends migration
{
	static public function depends_on()
	{
		return [
			'\dmzx\partner\migrations\v1_0_5'
		];
	}

	public function update_data()
	{
		return [
			['config.update', ['partner_version', '1.0.6']],
		];
	}

	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'dm_partners'		=> [
					'left_id'			=> ['UINT', 0, 'after' => 'id'],
					'right_id'			=> ['UINT', 0, 'after' => 'left_id'],
				],
			],
		];
	}
}

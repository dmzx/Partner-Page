<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

use phpbb\db\migration\migration;

class v1_0_4 extends migration
{
	static public function depends_on()
	{
		return ['\dmzx\partner\migrations\v1_0_3'];
	}

	public function update_data()
	{
		return [
			['config.update', ['partner_version', '1.0.4']],
		];
	}
}

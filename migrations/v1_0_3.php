<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

class v1_0_3 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\dmzx\partner\migrations\partner_install');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('partner_version', '1.0.3')),
		);
	}
}

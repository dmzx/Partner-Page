<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

class partner_module extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_DMP_PARTNERS')),
			array('module.add', array(
			'acp', 'ACP_DMP_PARTNERS', array(
					'module_basename'	=> '\dmzx\partner\acp\partner_module', 'modes' => array('acp_dmp_config'),
				),
			)),
		);
	}
}
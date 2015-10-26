<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\migrations;

class partner_data extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(

			// Add configs
			array('config.add', array('dm_partners_new', 1)),

			// Add permissions
			array('permission.add', array('a_dm_partners_edit', true)),
			array('permission.add', array('u_dm_partners_view', true)),
			array('permission.add', array('u_dm_partners_add', true)),

			// Set permissions
			array('permission.permission_set', array('REGISTERED', 'u_dm_partners_view', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'a_dm_partners_edit', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dm_partners_view', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dm_partners_add', 'group')),
		);
	}
}
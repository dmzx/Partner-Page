<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\acp;

class partner_info
{
	function module()
	{
		return array(
			'filename'		=> '\dmzx\partner\acp\partner_module',
			'title'			=> 'ACP_DM_EDS',
			'modes'			=> array(
				'acp_dmp_config'	=> array(
					'title'	=> 'ACP_DMP_PARTNERS', 'auth' => 'ext_dmzx/partner && acl_a_dm_partners_edit', 'cat' => array('ACP_DMP_PARTNERS')),
				'view'	=> array(
					'title'	=> 'ACP_DMP_PARTNERS', 'auth' => 'ext_dmzx/partner && acl_u_dm_partners_view', 'cat' => array('ACP_DMP_PARTNERS')),
				'add'	=> array(
					'title'	=> 'ACP_DMP_PARTNERS', 'auth' => 'ext_dmzx/partner && acl_u_dm_partners_add', 'cat' => array('ACP_DMP_PARTNERS')),
			),
		);
	}
}

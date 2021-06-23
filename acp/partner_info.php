<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\acp;

class partner_info
{
	function module()
	{
		return [
			'filename'		=> '\dmzx\partner\acp\partner_module',
			'title'			=> 'ACP_DM_EDS',
			'modes'			=> [
				'acp_dmp_config'	=> [
					'title'	=> 'ACP_DMP_CONFIG', 'auth' => 'ext_dmzx/partner && acl_a_dm_partners_edit', 'cat' => ['ACP_DMP_PARTNERS']
                ],
				'view'	=> [
					'title'	=> 'ACP_DMP_CONFIG', 'auth' => 'ext_dmzx/partner && acl_u_dm_partners_view', 'cat' => ['ACP_DMP_PARTNERS']
                ],
				'add'	=> [
					'title'	=> 'ACP_DMP_CONFIG', 'auth' => 'ext_dmzx/partner && acl_u_dm_partners_add', 'cat' => ['ACP_DMP_PARTNERS']
                ],
            ],
        ];
	}
}

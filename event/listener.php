<?php
/**
*
* @package phpBB Extension - Partner Page
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\partner\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $phpEx;

	/** @var string */
	protected $dm_partners_table;

	/**
	* Constructor
	*
	* @param \phpbb\user						$user
	* @param \phpbb\template\template			$template
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\config\config				$config
	* @param \phpbb\auth\auth					$auth
	* @param \phpbb\controller\helper			$helper
	* @param									$phpbb_root_path
	* @param									$phpEx
	* @param									$dm_partners_table
	*
	*/

	public function __construct(\phpbb\user $user, \phpbb\template\template $template, \phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\auth\auth $auth, \phpbb\controller\helper $helper, $phpbb_root_path, $phpEx, $dm_partners_table)
	{
		$this->user					= $user;
		$this->template				= $template;
		$this->db					= $db;
		$this->config				= $config;
		$this->auth 				= $auth;
		$this->helper 				= $helper;
		$this->phpbb_root_path 		= $phpbb_root_path;
		$this->phpEx 				= $phpEx;
		$this->dm_partners_table 	= $dm_partners_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'		=> 'load_language_on_setup',
			'core.page_header'		=> 'page_header',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dmzx/partner',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function page_header($event)
	{
		global $user;

		$l_new_partner = $s_new_partner = $new_entry = '';
		if ($this->user->data['user_type'] == 3)
		{
			$l_new_partner = '';
			$s_new_partner = false;

			if (!empty($this->config['dm_partners_new']))
			{
				$sql = 'SELECT * FROM ' . $this->dm_partners_table . '
					WHERE activ = 0';
				$result = $this->db->sql_query($sql);
				while ($row = $this->db->sql_fetchrow($result))
				{
					$new_entry = true;
				}
				$this->db->sql_freeresult($result);

				if($new_entry)
				{
					$l_new_partner = $this->user->lang['DMP_NEW_ENTRY'];
					$s_new_partner = true;
				}
				else
				{
					$l_new_partner = '';
					$s_new_partner = false;
				}
			}
		}

		$this->template->assign_vars(array(
			'L_DMP_NEW_ENTRY'		=> $l_new_partner,
			'S_NEW_PARTNER'			=> $s_new_partner,
			'U_PARTNERS'			=> $this->helper->route('dmzx_partner_controller'),
			'L_PARTNERS'			=> $this->user->lang['PARTNERS'],
			'U_ACP_PARTNER' 		=> ($this->auth->acl_get('a_') && !empty($this->user->data['is_registered'])) ? append_sid("{$this->phpbb_root_path}adm/index.$this->phpEx?sid=$user->session_id", false, true) : '',
		));
	}
}

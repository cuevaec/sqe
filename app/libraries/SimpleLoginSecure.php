<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.3/PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', false);

/**
 * SimpleLoginSecure Class
 *
 * Makes authentication simple and secure.
 *
 * Simplelogin expects the following database setup. If you are not using
 * this setup you may need to do some tweaking.
 *
 *
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `user_email` varchar(255) NOT NULL default '',
 *     `user_pass` varchar(60) NOT NULL default '',
 *     `user_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Creation date',
 *     `user_modified` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `user_last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `user_email` (`user_email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * @package   SimpleLoginSecure
 * @version   2.1.1
 * @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
 * @copyright Copyright (c) 2012-2013, Stéphane Bourzeix
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/DaBourz/SimpleLoginSecure
 */
class SimpleLoginSecure
{
	protected $CI;
	protected $user_table = 'persons';

    /**
     * Create a user account
     *
     * @access    public
     * @param string $forename
     * @param string $surname
     * @param string $user_email
     * @param string $user_pass
     * @param bool $auto_login
     * @internal param $string
     * @internal param $string
     * @return    bool
     */
	function create( $forename = '', $surname = '', $user_email = '', $user_pass = '', $isAdmin = 0, $auto_login = true)
	{
		$this->CI =& get_instance();

		//Make sure account info was sent
		if( $forename == '' OR $surname == '' OR $user_email == '' OR $user_pass == '') {
			return false;
		}

		//Check against user table
		$this->CI->db->where('username', $user_email);
		$query = $this->CI->db->get_where($this->user_table);

		if ($query->num_rows() > 0) //user_email already exists
			return false;

		//Hash user_pass using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$user_pass_hashed = $hasher->HashPassword($user_pass);

		//Insert account into the database
		$data = array(
                    'forename1' => $forename,
                    'surname' => $surname,
					'username' => $user_email,
					'password' => $user_pass_hashed,
					'isAdmin' => $isAdmin
				);

		$this->CI->db->set($data);

		if(!$this->CI->db->insert($this->user_table)) //There was a problem!
			return false;

		if($auto_login)
			$this->login($user_email, $user_pass);

        //Get new userId
        $this->CI->db->where('username', $user_email);
        $query = $this->CI->db->get_where($this->user_table);
        $newUser = $query->row_array();

		return $newUser['idUser'];
	}

	/**
	 * Update a user account
	 *
	 * Only updates the email, just here for you can
	 * extend / use it in your own class.
	 *
	 * @access	public
	 * @param integer
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function update($user_id = null, $user_email = '', $auto_login = true)
	{
		$this->CI =& get_instance();

		//Make sure account info was sent
		if($user_id == null OR $user_email == '') {
			return false;
		}

		//Check against user table
		$this->CI->db->where('idUser', $user_id);
		$query = $this->CI->db->get_where($this->user_table);

		if ($query->num_rows() == 0){ // user don't exists
			return false;
		}

		//Update account into the database
		$data = array(
					'username' => $user_email
				);

		$this->CI->db->where('idUser', $user_id);

		if(!$this->CI->db->update($this->user_table, $data)) //There was a problem!
			return false;

		if($auto_login){
			$user_data['username'] = $user_email;
			$user_data['user'] = $user_data['username']; // for compatibility with Simplelogin

			$this->CI->session->set_userdata($user_data);
			}
		return true;
	}

	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function login($user_email = '', $user_pass = '')
	{

		$this->CI =& get_instance();

		if($user_email == '' OR $user_pass == '')
			return false;

        $this->CI->db->where('username', $user_email);
        $query = $this->CI->db->get_where($this->user_table);
        $user_data = $query->row_array();

		//Check if already logged in
		if($this->CI->session->userdata('username') == $user_email
            && !empty( $user_data['idUser'] ) )
        {
            return $user_data['idUser'];
        }

		if (!empty( $user_data['idUser'] ))
        {

			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

			if(!$hasher->CheckPassword($user_pass, $user_data['password']))
				return false;

			//Destroy old session
			$this->CI->session->sess_destroy();

			//Create a fresh, brand new session
			$this->CI->session->sess_create();

			//$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET user_last_login = "' . date('c') . '" WHERE user_id = ' . $user_data['user_id']);

			//Set session data
            $sessionData = array(
                'username'=>$user_data['username'],
                'idUser' => $user_data['idUser'],
                'logged_in' => true,
                'isAdmin' => ( $user_data['isAdmin'] == 1 )
            );
			unset($user_data['password']);
			$this->CI->session->set_userdata($sessionData);

			return $user_data['idUser'];
		}
		else
		{
			return false;
		}

	}

	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout() {
		$this->CI =& get_instance();

		$this->CI->session->sess_destroy();
	}

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($user_id)
	{
		$this->CI =& get_instance();

		if(!is_numeric($user_id))
			return false;

		return $this->CI->db->delete($this->user_table, array('idUser' => $user_id));
	}


	/**
	* Edit a user password
	* @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
	* @author    Diego Castro <castroc.diego[at]gmail.com>
	*
	* @access  public
	* @param  string
	* @param  string
	* @param  string
	* @return  bool
	*/
	function edit_password($user_email = '', $old_pass = '', $new_pass = '')
	{
		$this->CI =& get_instance();
		// Check if the password is the same as the old one
		$this->CI->db->select('password');
		$query = $this->CI->db->get_where($this->user_table, array('username' => $user_email));
		$user_data = $query->row_array();

		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		if (!$hasher->CheckPassword($old_pass, $user_data['password'])){ //old_pass is the same
			return FALSE;
		}

		// Hash new_pass using phpass
		$user_pass_hashed = $hasher->HashPassword($new_pass);
		// Insert new password into the database
		$data = array(
			'password' => $user_pass_hashed
		);

		$this->CI->db->set($data);
		$this->CI->db->where('username', $user_email);
		if(!$this->CI->db->update($this->user_table, $data)){ // There was a problem!
			return FALSE;
		} else {
			return TRUE;
		}
	}

}
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model 
{
	public function get_mysqli() { 
		$db = (array)get_instance()->db;
		return mysqli_connect('p:'.$db['hostname'], $db['username'], $db['password'], $db['database']);
	}
	
    public function login($username,$password)
    {
        $query = "Select * From users where username=? and password=? and active = 'yes' ";
        
        $result = $this->db->query($query,array($username,$password))->result_array();
        return $result;
    }
    
    public function getCurrentUsername($user_id)
    {
        $query = "
        SELECT firstname, lastname
        FROM users
        WHERE user_id=?
        ";

        $result = $this->db->query($query, array($user_id))->first_row('array');
        return $result['firstname']. ' ' .$result['lastname'];
    }
	
    public function checkUserFromURL($username)
    {
        $query = "Select * From users where username=? and active = 'yes' ";
        
        $result = $this->db->query($query,array($username))->first_row('array');
        return $result;
    }
	
	public function getUserId($username)
    {
        $query = "Select user_id From users where username=? ";
        
        $result = $this->db->query($query,array($username))->first_row('array')['user_id'];
        return $result;
    }
    
    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE user_id=?";
        return $this->db->query($query, array($id))->first_row('array');
    }
    
    public function getUsers()
    {
        $query = "SELECT * FROM users order by date_created";
        return $this->db->query($query)->result_array();
    }
    
    public function addUser($username,$firstname, $lastname, $password, $user_level,$modified_by)
    {
        $query = "
        INSERT INTO users
        (username,firstname, lastname, password, user_level,modified_by)
        VALUES
        (?, ?, ?, ?, ?, ?)";

        if($this->db->query($query, array($username, $firstname, $lastname,  $password, $user_level,$modified_by)))
                return true;
        else
                return false;		
    }
    
    public function updateUser($firstname, $lastname, $user_level, $active, $last_modified,$modified_by,$user_id)
    {
        $query = "UPDATE `users` "
                    . "SET `firstname`=?, "
                    . "`lastname`=?, "
                    . "`user_level`=?, "
                    . "`active`=?, "                    
                    . "`last_modified`=?, "
                    . "`modified_by`=? "
                    . " WHERE user_id=?";
            
            //echo 'location '.$location.'<br/>division '.$division_abbre.'<br/>hidden id '.$asset_id;
               // die();
            
            if($this->db->query($query,array($firstname, $lastname, $user_level, $active, $last_modified,$modified_by,$user_id)))
                return true;
            return false;
    }
    
    public function testEmail($str)
    {
	$query = "SELECT * FROM users WHERE username = ? LIMIT 1;";
        return $this->db->query($query, array($str))->result_array();
    }
    
    public function testPassword($id, $pass)
    {
	$query = "SELECT * FROM users WHERE user_id=? AND password=?";
	return $this->db->query($query, array($id, $pass));
    }
    
    public function changeUserPassword($id, $pass)
    {
        $query = "UPDATE users SET password=? WHERE user_id=?";
        return $this->db->query($query, array($pass, $id));
    }
    
    public function isUserLoggedIn()
    {
        if(!isset($_SESSION['fa_user_id']) || empty($_SESSION['fa_user_id']) )
            redirect( "user" );
    }
    
    public function hasRole($userRole, $requiredRoles)
    {
        if(!in_array($userRole, $requiredRoles))
            redirect("main");
        
    }
	
	public function getUserByEmailAddress($email)
    {
        return $this->db->query("SELECT * FROM users WHERE username=?", array($email))->first_row('array');
    }
    
    public function saveResetEmailRequest($token, $email)
    {		
        if($this->db->query("INSERT INTO `reset_email_request`(`email`, `token`, `date_created`, `status`) VALUES (?,?,?,1)", array($email, $token, date('Y-m-d H:i:s'))))
            return true;
        else
            return false;
        
    }
	
    public function getResetPasswordRequestById($reset_id)
    {
        return $this->db->query("SELECT * FROM reset_email_request WHERE id=?", array($reset_id))->first_row('array');
    }
    
    public function reset_password($email, $password, $reset_id)
    {
        $this->db->trans_begin();

        $this->db->query("UPDATE users SET password=? WHERE username=?", array($password, $email));

        $this->db->query("UPDATE reset_email_request SET status=2 WHERE id=?", array($reset_id));

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
	
	public function logFailedAddUser($first_name, $last_name, $email, $password){
		$this->db->trans_begin();
		
		$this->db->query("INSERT INTO `failed_add_user`(`first_name`, `last_name`, `email`, `password`) VALUES(?,?,?,?)", array($first_name, $last_name, $email, $password));
		
		if($this->db->trans_status() === TRUE){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
			return false;
		}
	}
    
    
    
}
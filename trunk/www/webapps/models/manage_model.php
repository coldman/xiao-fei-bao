<?php
/**
 * Manager Model
 *
 *
 * @author  zhangxk <zxk0610@gmail.com>
 * @link 
 */

class manage_model extends MY_Model
{
    /**
	 * construct user model
	 * 
	 * @access public
	 * @return void
	 */    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
	 * aquire user info by username
	 * 
	 * @access public
	 * @param $username
	 * @return array
	 */

    public function get_user_by_username($username)
    {
        $query = $this->db->get_where('manage_users', array('user_name' => $username), 1);
		
		if($query->num_rows() == 1)
			return $query->row_array();
		
		return FALSE;
    }
    
    /**
	 * aquire all managers
	 *
	 * @access public
	 * @param $num integer items per page
	 * @param $offset integer pages offset
	 * @return object
	 */
	public function get_users_list($limit = 10, $offset = 0)
	{
		$this->db->from('manage_users');
		if($limit != 'ALL')
			$this->db->limit($limit, $offset);
		$this->db->order_by('username desc');
	 	$query = $this->db->get();
	 	
	 	return $query->result();
	}
    
    public function get_users_by_addtime($begin_time, $end_time, $limit = 10, $offset = 0)
	{
		$this->db->from('manage_users');
        
        $array = array('add_time >=' => $begin_time, 'add_time <=' => $end_time);
        $this->db->where($array);
        
		if($limit != 'ALL')
			$this->db->limit($limit, $offset);
		$this->db->order_by('username desc');
	 	$query = $this->db->get();
	 	
	 	return $query->result();
	}
    
    /**
	 * add a manager
	 * 
	 * @access public
	 * @param array - $manager manager info
	 * @return boolean
	 */
	public function add_user($manager)
	{
		$this->db->insert('manage_users', $manager);
		
		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}
    
    /**
	 * modify manager info
	 * 
	 * @access public
	 * @param int - $uid manager id
	 * @param array - $username manager'name info
	 * @return boolean
	 */
	public function update_user($uid, $username)
	{
		$this->db->where('user_id', $uid);
		$this->db->update('user_name', $username);
		
		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}
    
    /**
	 * validate username and password is match
	 * 
	 * @access public
	 * @param string - $username
	 * @param string - $password
	 * @return boolean
	 */
	public function validate_user($username, $password)
	{
		$query = $this->db->get_where('manage_users', array('user_name' => $username));
		
		if($query->num_rows() == 1)
		{
			$user = $query->row_array();
			return ($password == $user['password']) ? $user : FALSE;
		}
		
		return FALSE;
	}
    
    /*
    * 获取某个业务员名下的代理
    */
    public function get_users_by_managerid($managerid, $limit = 10, $offset = 0)
    {
        
        $this->db->from('users');
        
        $array = array('belong_manage' => $managerid);
        $this->db->where($array);
        
		if($limit != 'ALL')
			$this->db->limit($limit, $offset);
		$this->db->order_by('user_name desc');
	 	$query = $this->db->get();
	 	
	 	return $query->result();
        
    }
    
    /*
    * 获取代理营业额
    */
    public function get_agent_amt($agentid, $begin_time=0, $end_time=200000000000000000)
    {
        /*
        SELECT SUM(`goods_amount`) AS goods_am2ount FROM (`kvke_order_info`) LEFT JOIN 
        * `kvke_users` ON `kvke_users`.`user_id` = `kvke_order_info`.`app_user_id` 
        * WHERE `app_user_id` = 21417 AND `add_time` >= 1124106441 AND 
        * `add_time` < 1424106441;

        */
        $this->db->select_sum('goods_amount');
        $this->db->from('order_info');
        $this->db->join('users', 'users.user_id = order_info.app_user_id', 'left');
        
        $array = array('app_user_id'=>$agentid, 'add_time >=' => $begin_time, 
                       'add_time <' => $end_time);
        $this->db->where($array);
        
        return $this->db->get()->result();
    }
    
    /*
    * 获取代理列表
    */
    public function get_agent_list($limit = 10, $offset = 0)
	{
		
        $this->db->select('user_id','user_name','is_agent','');
        $array = array('is_agent'=>1);
        $this->db->where($array);
		if($limit != 'ALL')
			$this->db->limit($limit, $offset);
		$this->db->order_by('user_name desc');
	 	$query = $this->db->get('users');
	 	
	 	return $query->result();
	}
    
    /*
    * 获取已代理的省
    */
    public function get_marker_province()
	{
		
        $sql = "SELECT * FROM kvke_region WHERE region_type=? AND 
                region_id in (SELECT province FROM kvke_users)";

        return $this->db->query($sql, array(1))->result();
        
	}
    
    /*
    * 获取已代理的市
    */
    public function get_marker_city($parent_id)
	{
		
        $sql = "SELECT * FROM kvke_region WHERE region_type=? AND  parent_id=? AND
                region_id in (SELECT city FROM kvke_users where province=?)";

        return $this->db->query($sql, array(2, $parent_id, $parent_id))->result();
        
	}
    
    /*
    * 获取已代理的区
    */
    public function get_marker_district($parent_id)
	{
		
        $sql = "SELECT * FROM kvke_region WHERE region_type=? AND  parent_id=? AND
                region_id in (SELECT district FROM kvke_users where city=?)";

        return $this->db->query($sql, array(3, $parent_id, $parent_id))->result();
        
	}

}
?>

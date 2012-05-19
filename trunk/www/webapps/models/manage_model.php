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
	 
	 */    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * aquire user info by username
     */

    public function get_user_by_username($username)
    {
        $query = $this->db->get_where('manage_users', array('user_name' => $username), 1);
		
		if($query->num_rows() == 1)
			return $query->row_array();
		
		return FALSE;
    }
    
    /**
     * Get manage grid data
     */
    function get_manage_grid_data($params=array())
    {
	$result = array(
	    'Total'=>0, 
	    'Rows'=>array()
	);
	$tb_name = 'manage_users';
	$result['Total'] = $this->db->count_all_results($tb_name);
	if (array_key_exists('limit', $params))
	{
	    $offset = isset($params['offset'])?$params['offset']:0;
	    $this->db->limit($params['limit'], $offset);
	}
	$result['Rows'] = $this->db->get($tb_name)->result();

	return $result;
    }
    
    /**
     * aquire all managers
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
    * 获取某个业务员名下的所有代理
    */
    public function get_agents_by_manager_id($manager_id, $limit = 20, $offset = 0,
                                             $sortname="user_name", $sortorder="desc")
    {
       
        $result = array("Total"=>0,"Rows"=>array());

        //$sql = "select count(*) as total FROM kvke_users WHERE manage_id=?";
        //$total =  $this->db->query($sql, array($manager_id))->result();
        $this->db->where(array('manage_id' => $manager_id));
        $total = $this->db->count_all_results("users");
        
        if ($total){
            $result["Total"] = $total;
            
            $this->db->select("user_name,real_name,email,sex,qq,msn,comp_phone,comp_name");
            $this->db->where(array('manage_id' => $manager_id));
            $this->db->limit($limit, $offset);
            $this->db->order_by("$sortname $sortorder");
            $result["Rows"] = $this->db->get("users")->result();
        }
        
        return $result ;
        
    }
    
    /*
    * 获取代理营业额
    */
    public function get_agent_amt($agent_id, $begin_time=FALSE, $end_time=FALSE)
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
        
        $array = array('app_user_id'=>$agent_id, 'add_time >=' => $begin_time, 
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
    * 获取未代理的省
    */
    public function get_unmarked_province()
	{
		
        $sql = "SELECT * FROM kvke_region WHERE region_type=? AND 
                region_id not in (SELECT province FROM kvke_users)";
        return $this->db->query($sql, array(1))->result();
        
    }
    
    /*
    * 获取未代理的市
    */
    public function get_unmarked_city($parent_id=FALSE)
	{
		if ($parent_id==FALSE)
        {
            $sql = "SELECT * FROM kvke_region WHERE region_type=? AND
                    region_id not in (SELECT city FROM kvke_users)";
            return $this->db->query($sql, array(2))->result();
        }   
        else
        {    
            $sql = "SELECT * FROM kvke_region WHERE region_type=? AND  parent_id=? AND
                region_id not in (SELECT city FROM kvke_users where province=?)";
            return $this->db->query($sql, array(2, $parent_id, $parent_id))->result();
        }
	}
    
    /*
    * 获取未代理的区
    */
    public function get_unmarked_district($parent_id=FALSE)
	{
		if ($parent_id==FALSE)
        {
            $sql = "SELECT * FROM kvke_region WHERE region_type=? AND
                    region_id not in (SELECT district FROM kvke_users)";
            return $this->db->query($sql, array(3))->result();
        }
        else
        {    
            $sql = "SELECT * FROM kvke_region WHERE region_type=? AND  parent_id=? AND
                    region_id not in (SELECT district FROM kvke_users where city=?)";
            return $this->db->query($sql, array(3, $parent_id, $parent_id))->result();
        }
	}
    /*
    * 获取商户出单数量
    */
    public function get_trader_orders($tradeid,$begin_time=FALSE,$end_time=FALSE)
	{
		
        $sql = "SELECT count(*) FROM kvke_order_info WHERE user_id=? AND pay_time 
                between ? AND ?";

        return $this->db->query($sql, array($tradeid, $begin_time, $end_time))->result();
        
	}
    
    /*
    * 插入考核表
    */
    //public function insert_assess_his


}
?>

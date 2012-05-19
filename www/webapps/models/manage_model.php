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
     * Get manage by id
     */
    function get_manage_by_id($id)
    {
	$tb_name = 'manage_users';
	$this->db->where('id', $id);
	$result = $this->db->get($tb_name)->row_array();
	return $result;
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
	$this->db->where('role_type', 0);
	$result['Total'] = $this->db->count_all_results($tb_name);
	$this->db->where('role_type', 0);
	if (array_key_exists('limit', $params) and $params['limit']>0)
	{
	    $offset = isset($params['offset'])?$params['offset']:0;
	    $this->db->limit($params['limit'], $offset);
	}
	$result['Rows'] = $this->db->get($tb_name)->result();

	return $result;
    }

    /**
     * Insert or update manager's info
     */
    function save_manage($data)
    {
	$tb_name = 'manage_users';
	if (array_key_exists('id', $data)) 
	{
	    $this->db->where('id', $data['id']);
	    $this->db->update($tb_name, $data);
	    $id = $data['id'];
	}
	else 
	{
	    $this->db->insert($tb_name, $data);
	    $id = $this->db->insert_id();
	}
	return $id;
    }
    
    /**
     * aquire all managers
     */
	public function get_users_list($limit = 10, $offset = 0)
	{
		$this->db->from('manage_users');
		if($limit > 0)
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
        
		if($limit > 0)
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
    function get_agent_grid_data($params)
    {
	$result = array(
	    'Total'=>0, 
	    'Rows'=>array()
	);
	$tb_name = 'users';
        $this->db->select("user_name,real_name,email,sex,qq,msn,comp_phone,comp_name");
	if (array_key_exists('manage_id', $params))
	{
	    $this->db->where('manage_id', $params['manage_id']);
	}
	$result['Total'] = $this->db->count_all_results($tb_name);
	if (array_key_exists('manage_id', $params))
	{
	    $this->db->where('manage_id', $params['manage_id']);
	}
	if (array_key_exists('limit', $params))
	{
	    $offset = isset($params['offset'])?$params['offset']:0;
	    $this->db->limit($params['limit'], $offset);
	}
	if (array_key_exists('sortname', $params))
	{
	    $sortorder = isset($params['sortorder'])?$params['sortorder']:'desc';
	    $this->db->order_by($params['sortname'], $params['sortorder']);
	}
	$result['Rows'] = $this->db->get($tb_name)->result();
	return $result;
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
    * 获取某代理商下所有商家数据
    */
    
    public function get_traders_grid_data($params=array())
    {
        $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
       
        
        
        if (!array_key_exists('manage_id')){
            return $result;
        }
        
        
        $sql = "SELECT user_id,user_name,district 
                WHERE district IN (SELECT district FROM kvke_users WHERE manage_id=?) ";
        if (array_key_exists('limit')){
            $sql = $sql." and limit=".$params['limit'];
        }
        
        $result['Rows'] = $this->db->query($sql, array($manage_id))->result();
        
        return $result;
    }
    
    /*
    * 获取代理列表
    */
    public function get_agent_list_grid_data($params=array())
    {
        $tb_name = 'users';
        $result = array('Total'=>0,
                        'Rows'=>array()
                        );
                        
        
        $this->db->where('is_agent', 1);
        $result['Total'] = $this->db->count_all_results($tb_name);
        
        $this->db->select('user_id,user_name,real_name,country,province,city,district');
        
        if (array_key_exists('limit',$params) and $params['limit'] > 0){
            $offset = isset($params['offset'])?$params['offset']:0;
            $this->db->limit($params['limit'], $offset);
        }
     

        $this->db->order_by('user_name desc');
        $result['Rows'] = $this->db->get($tb_name)->result();
        
        return $result;
    }
    

}
?>

<?php
/**
 * Manager Model
 *
 *
 * @author  zhangxk <zxk0610@gmail.com>
 * @link
 *
 *1.get_manage_by_id            - 获取某个业务员
 *2.get_manage_grid_data        - Get manage grid data
 *3.save_manage                 - Insert or update manager's info
 *4.add_user                    - add a manager
 *5.update_user                 - modify manager info
 *6.validate_user               - validate username and password is match
 *7.get_agent_grid_data         - 获取某个业务员名下的所有代理
 *8.get_agent_by_id             - 取某个业务员名下的所有代理
 *9.get_agent_amt               - 获取代理营业额
 *10.get_unmarked_province      - 获取未代理的省
 *11.get_unmarked_city          - 获取未代理的市
 *12.get_unmarked_district      - 获获取未代理的区
 *13.get_trader_orders          - 获取商户出单数量
 *14.get_traders_grid_data      - 获取某代理商下所有商家数据
 *15.bind_agents_to_manager     - Bind agents to manager
 *16.get_user_by_id             - 取某个用户(包括代理商)详细信息
 *17.get_trader_total_orders    - 获取商户出单数
 *18.get_agents_plan            - 获取代理商计划额度
 *19.get_undispatch_agents      - 获取未指派业务员的代理商列表
 *20.set_manage_to_agent        - 指派代理商给业务员

 
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
     * 获取某个业务员
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
    if (array_key_exists('id', $data) && $data['id']) 
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
     * add a manager
     */
    public function add_user($manager)
    {
        $this->db->insert('manage_users', $manager);
        
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * modify manager info
     */
    public function update_user($uid, $username)
    {
        $this->db->where('user_id', $uid);
        $this->db->update('user_name', $username);
        
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * validate username and password is match
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
        
        $tb_name = 'kvke_users';
        $this->db->where('is_agent',1);
        $where = "a.is_agent=1";
        if (array_key_exists('manage_id', $params))    // 经理
        {
            $manage_id = $params['manage_id'];
            $where = "a.is_agent=1 and a.manage_id=$manage_id";
            $this->db->where('manage_id',$params['manage_id']);
        } 
        
        $result['Total'] =   $this->db->count_all_results($tb_name);  //代理商个数
        
        $limit = 0;
        $offset = 0;
        $sortname = 'user_name';
        $sortorder = 'desc';
        if (array_key_exists('limit', $params))
        {
            $offset = isset($params['offset'])?(int)$params['offset']:0;
            $limit = (int)$params['limit'];
        }
        
        if (array_key_exists('sortname', $params))
        {
            $sortname = $params['sortname'];
        }
        
        if (array_key_exists('sortorder', $params))
        {
            $sortorder = $params['sortorder'];
        }
        
        
        $sql = "select T.user_id, T.user_name,T.real_name,T.province_name,T.city_name,T.district_name,T.comp_name,M.step1,M.step2,M.step3,M.step4,M.amount FROM
                (
                    select A.*,B.region_name as district_name FROM 
                    (select A.*,B.region_name as city_name FROM 
                    (select a.user_id,a.user_name,a.real_name,a.province,a.city,a.district,
                     a.comp_name,a.manage_id,b.region_name as province_name 
                     from kvke_users a left join kvke_region b on a.province=b.region_id 
                     where $where) A LEFT JOIN kvke_region B on A.city=B.region_id
                ) A LEFT JOIN kvke_region B on A.district=B.region_id
                ) T
                LEFT JOIN kvke_agents M ON T.user_id=M.agent_id
                order by T.$sortname $sortorder
                limit $limit offset $offset";
        
       
       $rows_array = $this->db->query($sql, array())->result();
       
       
       foreach ($rows_array as $row)
       {
          $array_plan = $this->get_agents_plan($row->user_id);
          $row->step1_plan = $array_plan['step1']/100.0;
          $row->step2_plan = $array_plan['step2']/100.0;
          $row->step3_plan = $array_plan['step3']/100.0;
          $row->step4_plan = $array_plan['step4']/100.0;
          
          $row->step1 = $row->step1/100.00;
          $row->step2 = $row->step2/100.00;
          $row->step3 = $row->step3/100.00;
          $row->step4 = $row->step4/100.00;
       } 
       $result['Rows'] = $rows_array;
       return $result;
    }
    
    
    
    /*
    * 获取某个业务员名下的所有代理
    */
    /*
    function get_agent_grid_data($params)
    {
        $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
        $tb_name = 'users';
        if (array_key_exists('manage_id', $params))
        {
            $this->db->where('manage_id', $params['manage_id']);
        }
        if (array_key_exists('is_agent', $params))
        {
            $this->db->where('is_agent', $params['is_agent']);
        }
        $result['Total'] = $this->db->count_all_results($tb_name);
        
        if (array_key_exists('select', $params))
        {
            $this->db->select($params['select']);
        }
        if (array_key_exists('manage_id', $params))
        {
            $this->db->where('manage_id', $params['manage_id']);
        }
        if (array_key_exists('is_agent', $params))
        {
            $this->db->where('is_agent', $params['is_agent']);
        }
        if (array_key_exists('limit', $params))
        {
            $offset = isset($params['offset'])?$params['offset']:0;
            $this->db->limit($params['limit'], $offset);
        }
        if (array_key_exists('sortname', $params))
        {
            $sortorder = isset($params['sortorder'])?$params['sortorder']:'desc';
            $this->db->order_by($params['sortname'], $params['sortname']);
        }
        $result['Rows'] = $this->db->get($tb_name)->result();
        return $result;
    }
    */
    
    /**
     * 获取某个代理
     */
    function get_agent_by_id($id)
    {
        $tb_name = 'users';
        $this->db->where('user_id', $id);
        $result = $this->db->get($tb_name)->row_array();
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
    * 获取某业务员下所有商家数据
    */
    public function get_traders_grid_data($params=array())
    {
        $tb_name = 'users';
        
        $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
       
	$this->db->select('district');
	if (array_key_exists('manage_id', $params))
	{
	    $this->db->where('manage_id', $params['manage_id']);
	}    
	$districts = $this->db->get($tb_name)->result_array();
	$dis_list  = array();
	foreach ($districts as $district)
	{
	    array_push($dis_list, $district['district']);
	}

	$this->db->where_in('district', $dis_list);
	$this->db->where('is_agent = 0');
	$result['Total'] = $this->db->count_all_results($tb_name);

	$this->db->select('user_id, user_name, real_name, province, city, district,comp_name');
	$this->db->where_in('district', $dis_list);
	$this->db->where('is_agent', 0);
	if (array_key_exists('limit', $params) and $params['limit'] > 0)
	{
	    $offset = isset($params['offset'])?$params['offset']:0;
	    $this->db->limit($params['limit'], $offset);
	}
	$records = $this->db->get($tb_name)->result();
	$region_ids = array();
	foreach ($records as $record)
	{
	    array_push($region_ids, $record->province);
	    array_push($region_ids, $record->city);
	    array_push($region_ids, $record->district);
	}
	$this->db->select('region_id, region_name');
	$this->db->where_in('region_id', $region_ids);
	$regions = $this->db->get('region')->result();
	$region_dict = array('0'=>'');
	foreach ($regions as $region)
	{
	    $region_dict[$region->region_id] = $region->region_name;
	}
	foreach ($records as $record)
	{
	    $record->province = $region_dict[$record->province];
	    $record->city     = $region_dict[$record->city];
	    $record->district = $region_dict[$record->district];
        
        //增加出单数
        $total_orders = $this->get_trader_total_orders($record->user_id);
        $record->total_orders = $total_orders['total_orders'];
        
	}
	$result['Rows'] = $records;
            return $result;
    }
    
    /**
     * Bind agents to manager
     */
    function bind_agents_to_manager($manager_id, $agents=array())
    {
        $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
        if (!array_key_exists('manage_id', $params)){
            return $result;
        }
        $manage_id = $params['manage_id'];
        
        $tb_name = 'users';
        $this->db->where('is_agent', 1);
        $this->db->where_in('user_id', $agents);
        $this->db->update($tb_name, array('manage_id'=>$manager_id));
        return true;
    }
   
    /**
     * 获取某个用户(包括代理商)详细信息
     */
    function get_user_by_id($id)
    {
        $tb_name = 'users';
        $this->db->where('user_id', $id);
        $result = $this->db->get($tb_name)->row_array();
        return $result;
    }
    
    /*
    * 获取商户出单数
    */
    function get_trader_total_orders($id, $begin_time=0, $end_time=200000000000)
    {
        
        $sql = "select count(*) as total_orders from kvke_order_info 
                where user_id=$id and pay_time between $begin_time and $end_time";
        return $this->db->query($sql)->row_array();
        
    }
    
    /*
    * 获取代理商计划额度
    */
    function get_agents_plan($id)
    {
        $sql = "select step1, step2, step3, step4 from kvke_agents_plan where agent_id=$id";
        return $this->db->query($sql)->row_array();
    }
    
    /*
    * 获取未指派业务员的代理商列表
    */
    function get_undispatch_agents($params=array())
    {
        $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
        $tb_name = 'users';
        
        $this->db->where(array('is_agent'=>1,'manage_id'=>0));
        $result['Total'] = $this->db->count_all_results($tb_name);
        
        $this->db->select('user_id','user_name');
        if (array_key_exists('limit', $params) and $params['limit'] > 0)
        {
            $offset = isset($params['offset'])?$params['offset']:0;
            $this->db->limit($params['limit'], $offset);
        }
        
        $this->db->where(array('is_agent'=>1,'manage_id'=>0));  //为指派业务员的代理
        $result['Rows'] = $this->db->get($tb_name)->result();
        
        return $result;
    }
    
    /*
    *指派代理商给业务员
    * manage_id=0  -  代理商取消业务员管理
    */
    function set_manage_to_agent($agent_id, $manage_id=0)
    {
        try 
        { 
            $data= array('manage_id'=>$manage_id);
            $this->db->where(array('user_id'=>$agent_id, 'is_agent'=>1)); //保证是代理商
            $this->db->update('users', $data); 
            return True;
        } 
        catch(Exception $e) 
        { 
            return False;
        }
    }
        

}
?>

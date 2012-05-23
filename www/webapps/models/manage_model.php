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
 *10.get_areas                - 获取未代理的省
 *11.
 *12.
 *13.get_trader_orders          - 获取商户出单数量
 *14.get_traders_grid_data      - 获取某代理商下所有商家数据
 *15.bind_agents_to_manager     - Bind agents to manager
 *16.get_user_by_id             - 取某个用户(包括代理商)详细信息
 *17.get_trader_total_orders    - 获取商户出单数
 *18.get_agent_plan            - 获取代理商计划额度
 *19.get_undispatch_agents      - 获取未指派业务员的代理商列表
 *20.set_manage_to_agent        - 指派代理商给业务员
 *21.set_agent_plan             - 设置代理商额度
 *22.insert_agent_log           - 记录代理商日志
 *23.del_manager                  - 删除业务员

 
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
        $where = "where  is_agent=1 ";
        
        
        $manage_id = 0;
        if (array_key_exists('manage_id', $params))    // 普通业务员
        {
            $manage_id = $params['manage_id'];
        } 
        
        $result['Total'] =   $this->db->count_all_results($tb_name);  //代理商个数(所有)
        
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
        
        
        //前半部分是自己的代理商   manage_id 返回在结果中
        
        
        $sql = "select a.user_id,a.user_name,a.comp_name,a.real_name,a.province,a.city,a.district, c.id as manage_id,c.username as manage_name, if(c.role_type=1,c.id, if(a.manage_id=$manage_id, $manage_id, 0)) as m_id,
                  b.amount, b.step1, b.step2, b.step3, b.step4,
                  d.step1 as step1_plan,d.step2 as step2_plan,d.step3 as step3_plan,d.step4 as step4_plan,c.username as manage_name
                from kvke_users a
                left join kvke_agents b on a.user_id=b.agent_id
                left join kvke_manage_users c on a.manage_id=c.id
                left join kvke_agents_plan d on a.user_id=d.agent_id
                where a.is_agent=1
                order by m_id desc";
        //echo $sql;
                
        /*
        $sql = "select A.*,M.step1,M.step2,M.step3,M.step4,M.amount
                from 
                    (select a.user_id,a.user_name,a.real_name,a.province,a.city,a.district,
                    a.comp_name,a.manage_id from kvke_users a
                    left join ( select * from kvke_users $where) b
                    on a.user_id=b.user_id 
                    where a.is_agent=1
                    order by b.user_id desc) A
                 LEFT JOIN kvke_agents M ON A.user_id=M.agent_id 
                 limit $limit offset $offset" ;
        */
       
       
        $rows_array = $this->db->query($sql)->result();
       
        foreach ($rows_array as $row)
        {
            /*
            $array_plan = $this->get_agent_plan($row->user_id);
            $row->step1_plan = $array_plan['step1']/100.0;
            $row->step2_plan = $array_plan['step2']/100.0;
            $row->step3_plan = $array_plan['step3']/100.0;
            $row->step4_plan = $array_plan['step4']/100.0;

            $row->step1 = $row->step1/100.00;
            $row->step2 = $row->step2/100.00;
            $row->step3 = $row->step3/100.00;
            $row->step4 = $row->step4/100.00;
            
            */
            $sql = "select region_name as pro_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->province))->row_array();
            $pro_name = '';
            if ($temp){
                $pro_name = $temp['pro_name'];
            }

            $sql = "select region_name as city_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->city))->row_array();
            $city_name = '';
            if ($temp){
                $city_name = $temp['city_name'];
            }

            $sql = "select region_name as dis_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->district))->row_array();
            $dis_name = '';
            if ($temp){
                $dis_name = $temp['dis_name'];
            }

            $row->province_name = $pro_name;
            $row->city_name = $city_name;
            $row->district_name = $dis_name;
            
       } 
       $result['Rows'] = $rows_array;
       
       
       return $result;
    }
    
    
    /**
     * 获取某个代理
     */
    function get_agent_by_id($id)
    {
       
         $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
        $tb_name = 'users';
        
        $this->db->where(array('is_agent'=>1,'manage_id'=>$id));
        $result['Total'] = $this->db->count_all_results($tb_name);
        
        $this->db->select('user_id,user_name,real_name,province,city,district');
      
        $this->db->where(array('is_agent'=>1,'manage_id'=>$id));  //为指派业务员的代理
        
        $rows_array = $this->db->get($tb_name)->result();
        foreach ($rows_array as $row)
        {
            $sql = "select region_name as pro_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->province))->row_array();
            $pro_name = '';
            if ($temp){
                $pro_name = $temp['pro_name'];
            }
            
            $sql = "select region_name as city_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->city))->row_array();
            $city_name = '';
            if ($temp){
                $city_name = $temp['city_name'];
            }
            
            $sql = "select region_name as dis_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->city))->row_array();
            $dis_name = '';
            if ($temp){
                $dis_name = $temp['dis_name'];
            }
            
            $row->province = $pro_name;
            $row->city = $city_name;
            $row->district = $dis_name;
            
            
        } 
        $result['Rows'] = $rows_array; 
        
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
        if (!$begin_time){
            $t = date('Y-m-01',time()); //当月第一天
            $begin_time = strtotime( $t );
        }
        
        
        if (!$end_time){
            $end_time = time();
        }
        $this->db->select_sum('goods_amount');
        $this->db->from('order_info');
        $this->db->join('users', 'users.user_id = order_info.app_user_id', 'left');
        
        $array = array('app_user_id'=>$agent_id, 'add_time >=' => $begin_time, 
                       'add_time <' => $end_time);
        $this->db->where($array);
        
        return $this->db->get()->result();
    }
    
    
    /*
    * 获取地区信息
    */
    
    
    public function get_areas($parent_id=1)
    {
	/*
        $result = array("Total"=>0, "Rows"=>array());
        $this->db->where(array('parent_id'=>$parent_id));
        $result['Total'] = $this->db->count_all_results('region');
	 */
        
        $sql = "SELECT region_id, region_name, region_type FROM kvke_region WHERE parent_id=$parent_id";
        $pro_objs =  $this->db->query($sql)->result();
        foreach ($pro_objs as $pro_obj)
        {
            $p = $pro_obj->region_id;
            $this->db->where('province', $p);
            $this->db->or_where('city', $p);
            $this->db->or_where('district', $p);
            
            $pro_obj->enabled = 0;
            if ($this->db->count_all_results('users') > 0)
                $pro_obj->enabled = 1;
            
            if (($pro_obj->region_type==1) or ($pro_obj->region_type==2)){  //省市都有children
                $pro_obj->children = array();
	    }
        
        }
        
        $result = $pro_objs;
        return $result;
        
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
    function get_agent_plan($id)
    {
        $sql = "select step1, step2, step3, step4 from kvke_agents_plan where agent_id=$id";
        return $this->db->query($sql)->row_array();
    }

    function save_agent_plan($data)
    {
	$tb_name = 'agents_plan';
	$is_update = false;
	if (array_key_exists('id', $data) && $data['id'])
	{
	    $this->db->where('id', $data['id']);
	    $is_update = true;
	}
	if (array_key_exists('agent_id', $data) && $data['agent_id']) 
	{
	    $this->db->where('agent_id', $data['agent_id']);
	    $is_update = true;
	}
	if ($is_update)
	{
	    $this->db->update($tb_name, $data);
	}
	else 
	{
	    $this->db->insert($tb_name, $data);
	}
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
        
        $this->db->select('user_id,user_name,real_name,province,city,district');
      
        $this->db->where(array('is_agent'=>1,'manage_id'=>0));  //为指派业务员的代理
        
        $rows_array = $this->db->get($tb_name)->result();
        foreach ($rows_array as $row)
        {
            $sql = "select region_name as pro_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->province))->row_array();
            $pro_name = '';
            if ($temp){
                $pro_name = $temp['pro_name'];
            }
            
            $sql = "select region_name as city_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->city))->row_array();
            $city_name = '';
            if ($temp){
                $city_name = $temp['city_name'];
            }
            
            $sql = "select region_name as dis_name from kvke_region where region_id=?";
            $temp = $this->db->query($sql,array($row->city))->row_array();
            $dis_name = '';
            if ($temp){
                $dis_name = $temp['dis_name'];
            }
            
            $row->province = $pro_name;
            $row->city = $city_name;
            $row->district = $dis_name;
            
            
        } 
        $result['Rows'] = $rows_array; 
        
        return $result;
    }
    
    /*
    *指派代理商给业务员
    * manage_id=0  -  代理商取消业务员管理
    */
    function set_manage_to_agent($agent_ids, $manage_id=0)
    {
        try 
        { 
            $data= array('manage_id'=>$manage_id);
            $this->db->where('is_agent', 1); //保证是代理商
            $this->db->where('user_id', $agent_ids);
            $this->db->update('users', $data); 
            return true;
        } 
        catch(Exception $e) 
        { 
            return false;
        }
    }
    
    /*
     *设置代理商额度
     *$params=array('agent_id'=>XXX, data=>array('step1'=>XX, 'step2'=>XX, 'step3'=>XX, 'step4=>XX'))
    */
    function set_agent_plan($params=array())
    {
        try{
            $this->db->where('agent_id', $params['agent_id']);
            $this->db->update('agents_plan',$params['data']);
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
    
    /*
    *记录代理商日志 - 业务员设置或者更改代理商额度时调用
    * $params=array('manage_id'=>XX, 'agent_id'=>XX, 'step1'=>XX, 'step2'=>XX, 'step3'=>XX, 'step4'=>XX, 
    *               'description'=>XX)
    */
    function insert_agent_log($params=array())
    {
        try{
            $this->db->set($params); 
            $this->db->insert('agent_log'); 
            return true;
        }
        catch(Exception $e){
            return false;
        }
        
    }
    
    /*
    *删除业务员
    */
    function del_manager($id)
    {
        try{
            $this->db->where('id', $id);
            $this->db->delete('manage_users'); 
            return true;
        }
        catch(Exception $e){
            return false;
        }
    
    }
    
    /*
    * 获取系统代理商额度计划 
    */
    function get_all_agents_plan($params=array())
    {
        $result = array(
            'Total'=>0, 
            'Rows'=>array()
        );
        
        $result['Total'] = $this->db->count_all_results('agents_plan');  //代理商个数(所有)
        
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
        
        $sql = "select a.agent_id, b.user_name, b.real_name, a.step1, a.step2, a.step3, a.step4 from
                kvke_agents_plan a
                left join kvke_users b on a.agent_id=b.user_id
                order by b.$sortname
                limit $limit offset $limit";
        $result['Rows'] = $this->db->query($sql)->result();
        return $result;
    
    }
    
    
    /*
    *自动统计营业额
    */
    function auto_count_amount()
    {
       
        return false;
    }
    
    
    function dbtest()
    {
        $sql = "select region_name from kvke_region where region_id=?";
        $ret = $this->db->query($sql,array(122222))->row_array();
        print_r($ret);
        return false;
    }
    
    function get_agent_rates($params=array())
    {
	$result = array(
	    'Total'=>0, 
	    'Rows'=>array()   
	);
	$tb_name = 'agent_rate_template';
	$result['Total'] = $this->db->count_all_results($tb_name);
	if (array_key_exists('limit', $params) and $params['limit']>0)
	{
	    $offset = isset($params['offset'])?$params['offset']:0;
	    $this->db->limit($params['limit'], $offset);
	}
	$result['Rows'] = $this->db->get($tb_name)->result();
	return $result;
    }   

    function get_agent_rate_by_id($id)
    {
	$tb_name = 'agent_rate_template';
	$this->db->where('id', $id);
	$result = $this->db->get($tb_name)->row_array();
	return $result;
    }

    function save_agent_rate($data)
    {
	$tb_name = 'agent_rate_template';
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

    function del_agent_rate($id)
    {
	$tb_name = 'agent_rate_template';
	$this->db->where('id', $id);
	$this->db->delete($tb_name);
	return true;
    }

}
?>

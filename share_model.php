<?php
/**
 * Created by PhpStorm.
 * User: zl
 * Date: 2015/6/25
 * Time: 22:09
 */
class Share_model extends CI_Model{
    function __construct(){

    }
    function diff_ip($userid,$time,$client_ip){
        $this->db = $this->load->database('default',true);
        $last_time = time() - 24*60*60;
        $this->db->where('userid',$userid);
        $this->db->where('time >=',$last_time);
        $this->db->where('clientip',$client_ip);
        $result = count($this->db->get('tbHit')->result());
        if($result == 0){
           $data = array(
               'userid' => $userid,
               'clientip' => $client_ip,
               'time' => $time
           );
            $this->db->insert('tbHit',$data);
            return 'ok';
        }else{
            return 'error';
        }
    }
}
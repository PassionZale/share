<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_model");
        $this->load->model("api_model");
        $this->load->model("huodong_model");
        $this->load->library("encrypt");
        $this->load->library('session');
    }

    public function index()
    {
        $this->islogin = $this->user_model->is_login();
        if ($this->islogin) {
            $this->session->set_userdata('last_activity', time());
            $this->userdetail = $this->user_model->userDetail($this->session->userdata('user_data'));
        }
        $this->load->view('share/share');
    }

    public function checkLogin()
    {
        $ret = array();
        $this->islogin = $this->user_model->is_login();
        if ($this->islogin) {
            $this->session->set_userdata('last_activity', time());
            $this->userdetail = $this->user_model->userDetail($this->session->userdata('user_data'));
            if ($this->userdetail->type <= 1) {
                $action = $this->uri->segment(2) ? $this->uri->segment(2) : false;
                $array = array("partner", "custom", "tbcustom", "rakeback");
                $is_allow = in_array($action, $array);
                if ($is_allow) {
                    header("location:/member/index");
                }

            }
            $ret = array('info' => '已经登录可直接分享!', 'status' => 0);
        } else {
            $ret = array('info' => '未登录,请登录后分享!', 'status' => 1);
        }
        echo json_encode($ret);

    }

    public function processData()
    {
        $this->db = $this->load->database('default', true);
        $tmp = $this->input->post();
        $ret = $this->huodong_model->diff_ip($tmp['userid'],$tmp['time'],$tmp['client_ip']);
        if($ret['status' == 0]) $this->db->insert('tbHit',$ret['arr']);
        $this->output->set_content_type('application/json');    //设置返回的请求头type为json,否则无法返回中文
        echo json_encode($ret);
    }

    public function getData()
    {
        $this->islogin = $this->user_model->is_login();
        if ($this->islogin) {
            $this->session->set_userdata('last_activity', time());
            $this->userdetail = $this->user_model->userDetail($this->session->userdata('user_data'));
            if ($this->userdetail->type <= 1) {
                $action = $this->uri->segment(2) ? $this->uri->segment(2) : false;
                $array = array("partner", "custom", "tbcustom", "rakeback");
                $is_allow = in_array($action, $array);
                if ($is_allow) {
                    header("location:/member/index");
                }

            }

        } else {
            header('location:/common/login');
        }
        $tmp = $this->input->post();
        $shareto = $tmp['shareto'];
        switch ($shareto) {
            case 0:
                $shareto = 'Qzone';
                break;
            case 1:
                $shareto = 'sina';
                break;
            case 2:
                $shareto = 'tencent';
                break;
            case 3:
                $shareto = 'renren';
                break;
            case 4:
                $shareto = 'weixin';
                break;
        }
        $email = $this->session->userdata('user_data');
        $this->db->where('emali', $email);
        $data = array(
            'email' => $email,
            'points' => 1,
            'shareto' => $shareto
        );
        $this->db->insert('tbShare', $data);
    }
}

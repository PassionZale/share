<!Doctype html>
<?php
$tmp_id = (int)$this->input->get('id');
if (isset($this->userdetail)) {
    $this->load->helper('url');
    $this->db = $this->load->database('default', true);
    $this->db->where('email', $this->userdetail->email);
    $userid = $this->db->get('tbUser')->row()->userid;
    if ($tmp_id == false || $tmp_id != $userid) {
        header("location:/share/index?id=$userid");
    }
}
$ip_address = $this->session->userdata['ip_address'];
if (isset($tmp_id)) {
    $this->huodong_model->diff_ip($tmp_id, time(), $ip_address);
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>分享</title>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body style="margin-top:15%">
<div style="width:300px;margin:0 auto;">
    <div class="bdsharebuttonbox">
        <!--<a href="#" class="bds_more" data-cmd="more"></a>-->
        <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
        <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
        <a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
        <a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a>
        <a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
    </div>
</div>
<!--Info Modal-->
<div class="modal fade" id="send_info" style="top:30%;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id='info' class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p class="text-danger"><a href="http://user.dooforex.com">点击我跳转到登录界面</a></p>
            </div>
        </div>
    </div>
</div>

<!--分享-->
<script>
    window._bd_share_config = {
        "common": {
            "bdSnsKey": {},
            "bdText": "",
            "bdMini": "2",
            "bdMiniList": false,
            "bdPic": "",
            "bdStyle": "1",
            "bdSize": "24"
        }, "share": {}
    };
    with (document)0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
</script>
<script>
    $(function () {
        var $info_modal = $('#send_info');
        var sendInfo = function (info) {//bootstrap modal init and show info message func
            $info_modal.modal({
                keyboadr: true,
                show: true,
                backdrop: true
            });
            $('#info').html(info);
        }
        var $node_list = $('.bdsharebuttonbox a');
        var isLogin = function () { //判断用户是否登陆
            $.ajax({
                type: 'POST',
                url: '/share/checkLogin',
                dataType: 'json',
                success: function (ret) {
                    switch (ret.status) {
                        case 1:
                            sendInfo(ret.info);
                            break;
                        case 0:
                            console.log('is login');
                            break;
                        default:
                            console.log('lost controll');
                            break;
                    }
                }
            });
        }
        var dataBack = function (index) {//保存分享平台的索引,传入后台,并录入DB
            $.ajax({
                type: 'POST',
                url: '/share/getData',
                data: {'shareto': index},
                error: function () {
                    alert('分享失败');
                },
                success: function () {
                    console.log('分享成功');
                }
            });
        }
        var anonymous = function (i) {//最简单的闭包了 @_@,保存分享<a> list的序列
            $($node_list[i]).on('click', function () {
                console.log(i);
                isLogin();
                dataBack(i);
            });
        }
        for (var i = 0; i < $node_list.length; i++) {
            anonymous(i);
        }
    });
</script>
</body>
</html>

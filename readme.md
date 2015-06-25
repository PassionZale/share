###share
1.php+百度分享 写的一个分享功能.
2.能够记录哪个用户分享的哪个平台等信息.
3.并记录分享的链接被访问的次数.
###intro
1.tbShare 用于记录用户分享在哪些平台
2.tbHit 用于记录用户分享连接后,这个链接被访问的数量
3.tbUser 用于取出对应的userid
### update 2015-6-25
1.使用CI框架的URL类,将原页面url:xxxx/share/index -->xxxx/share/index?id=userid
  由于每个用户的userid在table中是主键,不会重复,因此每个用户分享出去的url都是唯一的;
2.当view加载时,执行顶部php code,从session中获取浏览器的ip_address,userid,及cookies中的id;
3.对id和userid进行判断,如果访问该'分享链接'的用户未登陆,或未在我平台注册,则cookies中的id就会0,进行比较时会自动转换成false;
  如果id和userid相等,说明是同一个用户访问该'分享链接',这种情况是不能被视为'分享链接'被访问过的,应该过滤掉;
4.将当前unix时间戳$time,$ip_address,$userid传入share_model进行相应DB CRUD操作.
### share_model
1.为防止恶意访问'分享链接',使点击量数据造假,用$time > $last_time这一条件来限制数据库记录;
2.即一天之内,同一个ip点击了'分享链接',只会在DB中录入一次;
3.tbHit表中,flag字段默认为0,为以后赠金活动做准备,当userid对应用户的'分享链接'被访问了x次后,赠y金,然后标记x条userid数据中的
  flag字段为1,这样就能统计用户兑换了多少次'分享次数',flag = 0的数据即为其还未兑换的.
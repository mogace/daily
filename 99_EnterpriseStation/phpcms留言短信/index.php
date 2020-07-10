<?php
defined('IN_PHPCMS') or exit('No permission resources.');
define('CACHE_MODEL_PATH',PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);

class index {
	private $db, $m_db, $M;
	function __construct() {
		$this->db = pc_base::load_model('sitemodel_model');
		$this->m_db = pc_base::load_model('sitemodel_field_model');
		$this->M = new_html_special_chars(getcache('formguide', 'commons'));
		$this->siteid = intval($_GET[siteid]) ? intval($_GET[siteid]) : get_siteid();
		$this->M = $this->M[$this->siteid];
	}
	
	/**
	 * 表单向导首页
	 */
	public function index() {
		$siteid = $this->siteid;
		$SEO = seo($this->siteid, '', L('formguide_list'));
		$page = max(intval($_GET['page']), 1);
		$r = $this->db->get_one(array('siteid'=>$this->siteid, 'type'=>3, 'disabled'=>0), 'COUNT(`modelid`) AS sum');
		$total = $r['sum'];
		$pages = pages($total, $page, 20);
		$offset = ($page-1)*20;
		$datas = $this->db->select(array('siteid'=>$this->siteid, 'type'=>3, 'disabled'=>0), 'modelid, name, addtime', $offset.',20', '`modelid` DESC');
		include template('formguide', 'index');
	}
	
	/**
	 * 表单展示
	 */
	public function show() {
		if (!isset($_GET['formid']) || empty($_GET['formid'])) {
			$_GET['action'] ? exit : showmessage(L('form_no_exist'), HTTP_REFERER);
		}
		$siteid = $_GET['siteid'] ? intval($_GET['siteid']) : 1;
		$formid = intval($_GET['formid']);
		$r = $this->db->get_one(array('modelid'=>$formid, 'siteid'=>$siteid, 'disabled'=>0), 'tablename, setting');
		if (!$r) {
			$_GET['action'] ? exit : showmessage(L('form_no_exist'), HTTP_REFERER);
		}
		$setting = string2array($r['setting']);
		if ($setting['enabletime']) {
			if ($setting['starttime']>SYS_TIME || ($setting['endtime']+3600*24)<SYS_TIME) {
				$_GET['action'] ? exit : showmessage(L('form_expired'), APP_PATH.'index.php?m=formguide&c=index&a=index');
			}
		}
		$userid = param::get_cookie('_userid');
		if ($setting['allowunreg']==0 && !$userid && $_GET['action']!='js') showmessage(L('please_login_in'), APP_PATH.'index.php?m=member&c=index&a=login&forward='.urlencode(HTTP_REFERER));
		if (isset($_POST['dosubmit'])) {
			$tablename = 'form_'.$r['tablename'];
			$this->m_db->change_table($tablename);
			
			$data = array();
			require CACHE_MODEL_PATH.'formguide_input.class.php';
			$formguide_input = new formguide_input($formid);
			$data = new_addslashes($_POST['info']);
			$data = new_html_special_chars($data);
			$data = $formguide_input->get($data);
			$data['userid'] = $userid;
			$data['username'] = param::get_cookie('_username');
			$data['datetime'] = SYS_TIME;
			$data['ip'] = ip();
			$dataid = $this->m_db->insert($data, true);
			if ($dataid) {
				if ($setting['sendmail']) {
					pc_base::load_sys_func('mail');
					$mails = explode(',', $setting['mails']);
					if (is_array($mails)) {
						foreach ($mails as $m) {
							sendmail($m, L('tips'), $this->M['mailmessage']);
						}
					}
				}
				$this->db->update(array('items'=>'+=1'), array('modelid'=>$formid, 'siteid'=>$this->siteid));
			}

            // 发送提醒短信
            // $this->sendSmsAliyun('15062316192', array('code' => 8988));

			showmessage(L('thanks'), APP_PATH);
		} else {
			if ($setting['allowunreg']==0 && !$userid && $_GET['action']=='js') {
				$no_allowed = 1;
			}
			pc_base::load_sys_class('form', '', '');
			$f_info = $this->db->get_one(array('modelid'=>$formid, 'siteid'=>$this->siteid));
			extract($f_info);
			$tablename = 'form_'.$r['tablename'];
			$this->m_db->change_table($tablename);
			$ip = ip();
			$where = array();
			if ($userid) $where = array('userid'=>$userid);
			else $where = array('ip'=>$ip);
			$re = $this->m_db->get_one($where, 'datetime');
			$setting = string2array($setting);
			if (($setting['allowmultisubmit']==0 && $re['datetime']) || ((SYS_TIME-$re['datetime'])<$this->M['interval']*60)) {
				$_GET['action'] ? exit : showmessage(L('had_participate'), APP_PATH.'index.php?m=formguide&c=index&a=index');
			}
			
			require CACHE_MODEL_PATH.'formguide_form.class.php';
			$formguide_form = new formguide_form($formid, $no_allowed);
			$forminfos_data = $formguide_form->get();
			$SEO = seo($this->siteid, L('formguide'), $name);
			if (isset($_GET['action']) && $_GET['action']=='js') {
				if(!function_exists('ob_gzhandler')) ob_clean();
				ob_start();
			}
			$template = ($_GET['action']=='js') ? $js_template : $show_template;
			include template('formguide', $template, $default_style);
			if (isset($_GET['action']) && $_GET['action']=='js') {
				$data=ob_get_contents();
				ob_clean();
				exit(format_js($data));
			}
		}
	}

    /**
     * @param string $phone 手机号
     * @param array $templateparam
     */
    public function sendSmsAliyun($phone, $TemplateParam)
    {

        $params = array ();

        // *** 需用户填写部分 ***
        //  必填：是否启用https
        $security = false;

        //  必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "";
        $accessKeySecret = "";

        //  必填: 短信接收号码
        $params["PhoneNumbers"] = $phone;

        //  必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "";

        //  必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "";

        // 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = $TemplateParam;

        //  可选: 设置发送短信流水号
        // $params['OutId'] = "12345";

        //  可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        // $params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        require PHPCMS_PATH."/phpcms/modules/smsali/SignatureHelper.php";
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            )),
            $security
        );

        return $content;
    }
}
?>
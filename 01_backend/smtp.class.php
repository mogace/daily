<?php

class smtpMail{
	private function do_command($cmd, $return_code){ // 发送命令
		fwrite($this->sock, $cmd);

		$response = fgets($this->sock);
		if (strstr($response, "$return_code") === false) {
			echo 'command error: '. $response . ' '. $cmd. ' '.$return_code;
			exit;
		}

		return true;
	}

	public function smtp_mail(){ // 链接ftp服务器
		$this->sock = fsockopen($this->host, $this->port, $errorno, $errorstr, 10);
		if (!$this->sock) {
			exit('Error'.$errorno.$errorstr);
		}

		$response = fgets($this->sock);
		if (strstr($response, "220") === false) {
			exit('Server Error'.$response);
		}
	}

	public function send_mail($from, $to, $subject){

		$this->do_command("HELO smtp.qq.com\r\n", 250);
		$this->do_command("AUTH LOGIN\r\n", 334);
		$this->do_command($this->user."\r\n", 334);
		$this->do_command($this->pass."\r\n", 235);
		$this->do_command("MAIL FROM:<".$from.">\r\n", 250);
		$this->do_command("RCPT TO:<".$to.">\r\n", 250);
		$this->do_command("DATA\r\n", 354);
		$this->do_command("Content-Type:text/html;\r\ncharset=gb2312\r\n\r\nhello\r\n.\r\n", 250);
		$this->do_command("QUIT\r\n", 221);

		return true;
	}
}
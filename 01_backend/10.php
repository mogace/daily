<?php 
	/**
	 * PDO数据库连接学习
	 * @author mogace
	 * @date 2019/10/22
	 *
	 * 
	 */

	try {
		$dsn = "mysql:host=localhost;dbname=blog";
		$db = new PDO($dsn, 'root', 'root');
		// 设置异常捕获
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("set names utf8");

		// 使用预处理语句
		$insert = $db->prepare("insert into t2 (age) values (?)");
		$insert->execute(["aiyou"]);
		$insert->execute(["aiyouyou"]);

	} catch (PDOException $err) {
		var_dump($err->getMessage());
	}
<?php

include_once "wxBizMsgCrypt.php";

// 第三方发送消息给公众平台
$encodingAesKey = "Hkuxv9HxmUTNSDYOokCi1ZWnsQjuvy5lc0QHBCmE0OS";
$token = "wangleigang";
$timeStamp = time();
$nonce = time().rand(10,99999);
$appId = "wx029d1751309779b7";

$postStr = file_get_contents('php://input');
//var_dump($postStr);
//extract post data
if (!empty($postStr)){
	//将xml格式字符串解析为对象
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
				</xml>";
	
	// 获取消息类型
	$postMsgType = $postObj -> MsgType;
	// 获取消息发送方
	$fromUsername = $postObj->FromUserName;
	// 获取消息接收
    $toUsername = $postObj->ToUserName;
    $time = time();
	
	if($postMsgType == 'event')
	{
		// 获取事件类型
		$event = $postObj -> Event;
		if($event == 'subscribe')
		{
			$msgType = "text";
			$contentStr = "欢迎来到雷刚的公众号![鼓掌]，输入以下编号获取响应资源\n1.获取雷刚得企鹅号\n2.获取雷刚得手机号\n3.获取雷刚得微信号\n4.获取雷刚得新浪好\n谢谢关注，欢迎畅聊，本人从事php开发[得意]";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
	}elseif($postMsgType == 'text')
	{
		$keyword = trim($postObj->Content);
		if(!empty( $keyword ))
		{
			if($keyword == '1')
			{
				$msgType = "text";
				$contentStr = "876339732";
			}elseif($keyword == '2')
			{
				$msgType = "text";
				$contentStr = "18691077519";
			}elseif($keyword == '3')
			{
				$msgType = "text";
				$contentStr = "WLG_629";
			}elseif($keyword == '4')
			{
				$msgType = "text";
				$contentStr = "Just_归来";
			}
			
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
	
		}else{
			echo "请输入一些东西......";
		}
	}
}else {
    echo "error";
    exit;
}


$text = "<xml>
            <FromUserName><![CDATA[fromUser]]></FromUserName>
            <CreateTime>$timeStamp</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[你好]]></Content>
        </xml>";


$pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
$encryptMsg = '';
$errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
if ($errCode == 0) {
    print("加密后: " . $encryptMsg . "\n");
} else {
    print($errCode . "\n");
}

$xml_tree = new DOMDocument();
$xml_tree->loadXML($encryptMsg);
$array_e = $xml_tree->getElementsByTagName('Encrypt');
$array_s = $xml_tree->getElementsByTagName('MsgSignature');
$encrypt = $array_e->item(0)->nodeValue;
$msg_sign = $array_s->item(0)->nodeValue;

$format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
$from_xml = sprintf($format, $encrypt);

// 第三方收到公众号平台发送的消息
$msg = '';
$errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if ($errCode == 0) {
    print("解密后: " . $msg . "\n");
} else {
    print($errCode . "\n");
}

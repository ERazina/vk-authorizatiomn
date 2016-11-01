<?php
	class vk
	{
		protected static $client_id = '5687176'; // ID приложения
		protected static $client_secret = 'yEhp6qGsH31bodMpqQIP'; // Защищённый ключ
		protected static $redirect_uri = 'http://localhost/vk-auth/vkoop.php'; // Адрес сайта
		protected static $code;//код?
		protected $token;
		protected $userId;

		public static function showlink()
		{//
			$params = array(
				'client_id' => self::$client_id,
				'redirect_uri' => self::$redirect_uri,
				'response_type' => 'code'
			);
			$url = 'http://oauth.vk.com/authorize';
			echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';
		}

		public function __construct($code)
		{
			self::$code = $code;
			$this->getToken();
			$this->getInfo();
		}

		public function getToken()
		{
			$params = array(
				'client_id' => self::$client_id,
				'client_secret' => self::$client_secret,
				'code' => self::$code,
				'redirect_uri' => self::$redirect_uri
			);
			$info = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
			//здесь должна быть проверка на error
			$this->token = $info['access_token'];
			$this->userId = $info['user_id'];
		}

		public function getInfo()
		{
			$params = array(
				'uids' => $this->userId,//юзер айди
				'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
				'access_token' => $this->token,
			);

			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
			//	//здесь должна быть проверка на error
			$userInfo = $userInfo['response'][0];
			//$result = true;
			//	
			//}
			//
			//if ($result) {
			echo "Социальный ID пользователя: " . $userInfo['uid'] . '<br />';
			echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
			echo "Ссылка на профиль пользователя: " . 'http://vk.com/' . $userInfo['screen_name'] . '<br />';
			echo "Пол пользователя: " . $userInfo['sex'] . '<br />';
			echo "День Рождения: " . $userInfo['bdate'] . '<br />';
			echo '<img src="' . $userInfo['photo_big'] . '" />';
			echo "<br />";
			//}
			//
			$_SESSION['user'] = $userInfo;
			$this->getGroups();

		}

		public function getGroups()
		{
			$params = array(
				'count' => '10',
				'extended' => '1',
				'fields' => 'name,description',
				'access_token' => $this->token,
			);

			$getGroups = json_decode(file_get_contents('https://api.vk.com/method/groups.get' . '?' . urldecode(http_build_query($params))), true);
			$getGroups = $getGroups['response'];
			//print_r($getGroups);
			foreach ($getGroups as $num => $info) {
				echo "Название группы" . $info['name'] ."<br />";
				//echo "Название группы" . $info['description'] ."<br />";
				//echo '<img src="' . $getGroups['photo'] . '" />';

			}

		}

		public function getStatus()
		{
			$params = array(
				'count' => '10',
				'extended' => '1',
				'fields' => 'name,description',
				'access_token' => $this->token,
			);

			$getGroups = json_decode(file_get_contents('https://api.vk.com/method/groups.get' . '?' . urldecode(http_build_query($params))), true);
			$getGroups = $getGroups['response'];
			//print_r($getGroups);
			foreach ($getGroups as $num => $info) {
				echo "Название группы" . $info['name'] ."<br />";
				//echo "Название группы" . $info['description'] ."<br />";
				//echo '<img src="' . $getGroups['photo'] . '" />';

			}

		}
	}
	if (isset($_GET['code'])) {
		$vk = new VK($_GET['code']);
		//print_r($vk);
		//$res = "https://api.vk.com/method/users.get?user_id=210700286&v=5.52"

		//послать запрос на получение access-token
	}
	else {
		vk::showlink();
	}
?>
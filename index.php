<?php
$client_id = '5687176'; // ID приложения
$client_secret = 'yEhp6qGsH31bodMpqQIP'; // Защищённый ключ
$redirect_uri = 'http://localhost/vk-auth/index.php'; // Адрес сайта

$url = 'https://oauth.vk.com/authorize';
$params = array(
	    'client_id'     => $client_id,
	    'redirect_uri'  => $redirect_uri,'response_type' => 'code');

echo $link = '<p><a href="' . $url .'?'. urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';

if (isset($_GET['code'])) {
    $params = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    );

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
   
    if (isset($token['access_token'])) {
        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
    }

    if (isset($userInfo['response'][0]['uid'])) {
        $userInfo = $userInfo['response'][0];
        $result = true;
    }
    if ($result) {
        echo "Социальный ID пользователя: " . $userInfo['uid'] . '<br />';
        echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
        echo "Ссылка на профиль пользователя: ". 'http://vk.com/' . $userInfo['screen_name'] . '<br />';
        echo "Пол пользователя: " . $userInfo['sex'] . '<br />';
        echo "День Рождения: " . $userInfo['bdate'] . '<br />';
        echo '<img src="' . $userInfo['photo_big'] . '" />'; echo "<br />";
    }
    $_SESSION['user'] = $userInfo;
}


?>
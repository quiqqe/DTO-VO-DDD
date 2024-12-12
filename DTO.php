<?php

// DTO
// namespace app\dto;

class UserDTO
{
    public string $username;
    public string $email;

    // конструктор для удобства инициализации
    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
    }
}

// Controller
// namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\dto\UserDTO;

class UserController extends Controller
{
    public function actionCreate()
    {
        // получаем данные из запроса (например, через форму)
        $username = Yii::$app->request->post('username');
        $email = Yii::$app->request->post('email');

        // создаем DTO объект
        $userDto = new UserDTO($username, $email);

        // передаем DTO в бизнес-логику или репозиторий для сохранения
        $this->saveUser($userDto);

        return $this->asJson([
            'message' => 'User created successfully',
            'user' => [
                'username' => $userDto->username,
                'email' => $userDto->email
            ]
        ]);
    }

    private function saveUser(UserDTO $userDto)
    {
        // сохраняем пользователя в базу данных через модель
        $userModel = new User();
        $userModel->username = $userDto->username;
        $userModel->email = $userDto->email;
        $userModel->save();
    }
}

<?php

// VO
//namespace app\vo;

use InvalidArgumentException;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        // Логика валидации email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address.");
        }

        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    // Пример метода сравнения
    public function equals(Email $email): bool
    {
        return $this->email === $email->getEmail();
    }
}

// Controller
// namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\vo\Email;
use app\models\User;

class UserController extends Controller
{
    public function actionCreate()
    {
        $emailInput = Yii::$app->request->post('email');

        try {
            // Создание объекта VO
            $email = new Email($emailInput);

            // Создание пользователя с использованием VO
            $user = new User();
            $user->email = $email->getEmail();  // Использование значения из VO
            $user->save();

            return $this->asJson(['message' => 'User created successfully']);
        } catch (InvalidArgumentException $e) {
            return $this->asJson(['error' => $e->getMessage()]);
        }
    }
}

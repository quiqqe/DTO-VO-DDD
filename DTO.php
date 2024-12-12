// dto
// namespace app\dto;

class UserDTO
{
    public string $username;
    public string $email;

    // Конструктор для удобства инициализации
    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
    }
}

// контроллер
// namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\dto\UserDTO;

class UserController extends Controller
{
    public function actionCreate()
    {
        // Получаем данные из запроса (например, через форму или API)
        $username = Yii::$app->request->post('username');
        $email = Yii::$app->request->post('email');

        // Создаем DTO объект
        $userDto = new UserDTO($username, $email);

        // Передаем DTO в бизнес-логику или репозиторий для сохранения
        $this->saveUser($userDto);

        // Отправляем ответ (например, JSON)
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
        // Пример: сохраняем пользователя в базу данных через модель
        $userModel = new \app\models\User();
        $userModel->username = $userDto->username;
        $userModel->email = $userDto->email;
        $userModel->save();
    }
}

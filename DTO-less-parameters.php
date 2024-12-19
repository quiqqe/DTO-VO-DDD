<?php

// Уменьшение количества параметров в методах

// TaskController::createTask()
// метод для создания задачи
public function createTask(array $settings, int $type = 1)
    {
      // данные сформированы, но видоизменяются в методе создания(!)
        if ($type === 0) {
            $settings['Task']->status = TaskStatusEnum::DRAFT->value;
        } else {
            $settings['Task']->add_to_queue_at = strtotime('now');
        }
        $settings['Task']->save();

        Task::compressTaskPhoto($settings['Task'], $settings['TaskPhoto']);

        $taskTagQuery = TaskTags::find()
            ->where(['id' => $settings['Task']->task_tag_list])
            ->all();

        if ($taskTagQuery) {
            foreach ($taskTagQuery as $_item) {
                $instance = new LinkTaskToTaskTags(['scenario' => LinkTaskToTaskTags::SCENARIO_SAVE_VIA_FORM]);
                $instance->task_id = $settings['Task']->id;
                $instance->task_tag_id = $_item->id;
                $instance->save();
            }
        }

        return $settings;
    }

// получение массива настроек
// TaskController::::getSettingsForCreatePage()
public static function getSettingsForCreatePage(): array
{
        $settings = [];
        // создается несколько моделей, передача данных неудобна
        // решение - передавать только их данные (не сами модели), и только необходимые
        $settings['Task'] = new Task();
        $settings['LinkTaskToTaskTags'] = new LinkTaskToTaskTags(
            ['scenario' => LinkTaskToTaskTags::SCENARIO_SAVE_VIA_FORM]
        );
        $settings['Task']->loadDefaultValues();
        // в данном случае иниц-ся, но данные отсутствуют, транспортируется только пустая модель
        $settings['TaskPhoto'] = new TaskPhoto();

        $settings['Task']->status = TaskStatusEnum::FORMALIZATION->value;
        $settings['Task']->assigned_id = Yii::$app->user->id;
        //... дальнейший код
}

class TaskDto extends BaseDto
{
  public int $status;
  public int $assigned_id;
  // ... дальнейшие атрибуты
}

// перенесли getSettingsForCreatePage в модель и переименовали
public static function getDtoForCreatePage(): TaskDto
{
  $dto = new TaskDto();
  $dto->load();

  $dto->status = TaskStatusEnum::FORMALIZATION->value;
  $dto->assigned_id = Yii::$app->user->id;

  // ... дальнейшее формирование/заполнение данных

  // ... можно использовать BaseDto (и реализовать соотв. метод) для работы с yii2 валидаторами
  $dto->validate();

  // ... дальнейшее формирование/заполнение данных

  return $dto;
}

// итоговый метод для создания задачи
public function createTask(TaskDto $dto, int $type = 1): ?Task
    {
       $task = new Task();
    
       $task->setAttributes($dto->asArray());
      
       return $task->save() ? $task : null;
    }


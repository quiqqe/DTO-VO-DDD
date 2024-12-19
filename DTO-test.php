<?php

namespace tests\unit;

use app\dto\TaskDto;
use app\models\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskCreate(): void
    {
        // Создаем DTO с тестовыми данными
        $dto = new TaskDto(
            title: 'Test Task',
            description: 'This is a test task.',
            status: TaskStatus::PENDING->value,
            assignedUserId: 1
        );

        // Создаем задачу из DTO
        $task = new Task();
        $task->setAttributes($dto->asArray());

        $this->assertTrue($task->validate(), 'Task should be valid.');
        $this->assertTrue($task->save(), 'Task should be saved successfully.');

        // Проверяем сохраненные данные
        $this->assertSame('Test Task', $task->title);
        $this->assertSame('This is a test task.', $task->description);
        $this->assertSame(Task::STATUS_PENDING, $task->status);
        $this->assertSame(1, $task->assigned_user_id);
    }
}

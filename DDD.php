<?php

// Сущность - "Заказ"
// namespace app\domain\order;

class Order
{
    private int $id;
    private float $amount;
    private string $status;

    public function __construct(int $id, float $amount, string $status)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->status = $status;
    }

    // Логика работы с заказом
}

// Агрегат - заказ
// namespace app\domain\order;

class OrderRepository
{
    public function save(Order $order)
    {
        // Логика сохранения заказа в базу данных
    }
}

// Сервис для создания заказа
// namespace app\domain\order;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(float $amount): Order
    {
        $order = new Order(1, $amount, 'NEW');
        $this->orderRepository->save($order);
        return $order;
    }
}


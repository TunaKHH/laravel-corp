<?php
namespace App\Services;

class TaskOrderService
{
    public function __construct()
    {
    }
    public function getOrderList()
    {
        return Order::all();
    }
    public function getOrderById($id)
    {
        return Order::find($id);
    }
    public function createOrder($data)
    {
        return Order::create($data);
    }
    public function updateOrder($id, $data)
    {
        $order = Order::find($id);
        $order->update($data);
        return $order;
    }
    public function deleteOrder($id)
    {
        $order = Order::find($id);
        $order->delete();
        return $order;
    }
}

<?php
namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function __construct()
    {
    }

    public function getTaskList()
    {
        return Task::all();
    }

    public function getTaskById($id)
    {
        return Task::find($id);
    }

    public function createTask($data)
    {
        return Task::create($data);
    }

    public function updateTask($id, $data)
    {
        $task = Task::find($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        $task->delete();
        return $task;
    }

    /*
     * 取得最後一筆任務
     * @param $lineGroupId
     * @return Task|\Exception
     */
    public function getLastTask($lineGroupId): Task | \Exception
    {
        return Task::where('line_group_id', $lineGroupId)->orderBy('id', 'desc')->firstOrFail();
    }
}

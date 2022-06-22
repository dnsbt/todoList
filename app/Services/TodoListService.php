<?php

use App\Exceptions\TodolistAccessRestrictedException;
use App\Exceptions\TodoListNotFoundException;
use App\Models\TodoList;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Database\Eloquent\Collection;

class TodoListService
{
    private Factory $auth;

    /**
     * @param Factory $auth
     */
    public function __construct(Factory $auth)
    {
        return $this->auth = $auth;
    }

    /**
     * @throws TodoListNotFoundException
     */
    public function getTodoList(int $id): TodoList
    {
        /** @var TodoList $todoList */
        $todoList =  TodoList::query()->where('id', $id)->first();
        if (!$todoList) {
            throw new TodoListNotFoundException();
        }

        return $todoList;
    }


    /**
     * @param string $title
     * @return TodoList
     */
    public function createTodoList(string $title): TodoList
    {
        $newTodoList = new TodoList();
        $newTodoList->title = $title;
        $newTodoList->user_id = $this->getUser()->getAuthIdentifier();

        $newTodoList->save();

        return $newTodoList;
    }

    /**
     * @param TodoList $todoList
     * @param string $title
     * @return void
     * @throws TodolistAccessRestrictedException
     */
    public function updateTodoList(TodoList $todoList, string $title): TodoList
    {
        $this->checkAccess($todoList);
        $todoList->title = $title;

        $todoList->save();

        return $todoList;
    }
    /**
     * @param TodoList $todoList
     * @return bool
     * @throws TodolistAccessRestrictedException
     * @throws Exception
     */
    public function deleteTodoList(TodoList $todoList): bool
    {
        $this->checkAccess($todoList);

        return $todoList->delete();
    }

    /**
     * @param TodoList $todoList
     * @return void
     * @throws TodolistAccessRestrictedException
     */
    public function checkAccess(TodoList $todoList): void
    {
        $user = $this->getUser();
        if ($todoList->user_id !== $user->getAuthIdentifier()) {
            throw new TodolistAccessRestrictedException();
        }
    }

    /**
     * @param TodoList $todoList
     * @param bool $isCompleted
     * @return void
     */
    public function setIsCompleted(TodoList $todoList, bool $isCompleted): void
    {
        if ($todoList->is_completed !== $isCompleted) {
            $todoList->is_completed = $isCompleted;
            $todoList->save();
        }
    }


    private function getUser(): Authenticatable
    {
        /** @var Authenticatable $user */
        $user = $this->auth->guard()->user();
        return $user;
    }

}

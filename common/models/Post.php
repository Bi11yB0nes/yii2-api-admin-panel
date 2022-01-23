<?php

namespace common\models;

class Post extends BasePost
{
    public static function findByUserId($id)
    {
        return static::findAll(['userId' => $id]);
    }

    public function serializeToArray()
    {
        $data = [];
        $author = $this->user->username;

        $data['title'] = $this->title;
        $data['content'] = $this->content;
        $data['date'] = $this->date;
        $data['author'] = $author;
        return $data;
    }
}

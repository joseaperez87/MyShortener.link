<?php

class LinksController
{
    static function saveLinkAction($data)
    {
        if (empty($data)) {
            return ['res' => false, "message" => "Пустой запрос"];
        }

        $fullUrl = $data->url ?? "";
        $user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 0;

        $link = new Links();
        if (!empty(trim($fullUrl)) || filter_var($fullUrl, FILTER_VALIDATE_URL)) {
            $link->full_url = trim(strip_tags($fullUrl));
            $link->short_url = generateShortUrl();
            $link->user_id = trim(strip_tags($user_id));
            if ($link->save()) {
                return ['res' => true];
            }
        } else {
            return ['res' => false, 'message' => 'Указанный URL-адрес недействителен'];
        }
    }
}
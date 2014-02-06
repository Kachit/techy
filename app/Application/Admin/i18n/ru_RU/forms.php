<?php
$list = array(
    'next' => 'Дальше',
    'apply' => 'Применить',
    'save' => 'Сохранить',
    'activate' => 'Активировать',
    'approve' => 'Утвердить',
    'confirm' => 'Подтвердить',
    'submit' => 'Отправить',
    'cancel' => 'Отменить',

    'errors' => array(
        'general' => 'Что-то случилось. Попробуйте позже.',
    ),

    'upload' => array(
        'unexpectedType' => 'Неверный формат.',
    ),

    'image' => array(
        'upload' => array(
            \Techy\Lib\Core\Exception\UploadError::UNKNOWN             => 'Ошибка загрузки изображения.',
            \Techy\Lib\Core\Exception\UploadError::NOT_EMPTY           => 'Изображение отсутствует.',
            \Techy\Lib\Core\Exception\UploadError::WRONG_DIMENSIONS    => 'Загруженное изображение должно иметь размеры 612х612 пикселей.',
            \Techy\Lib\Core\Exception\UploadError::UNEXPECTED_TYPE     => 'Неверный формат изображения.',
        ),
    ),
);
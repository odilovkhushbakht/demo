<?php

namespace App\Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    protected $table = 'notification_categories';
    protected $fillable = ['name', 'template_message', 'days_advance'];

    public const CATEGORIES = [
        ['id' => 1, 'label' => 'Паспорт'],
        ['id' => 2, 'label' => 'Достижения совершеннолетия коллег'],
        ['id' => 3, 'label' => 'Окончания декрета'],
        ['id' => 4, 'label' => 'Окончания отпуск по ухаживанию за ребенком'],
        ['id' => 5, 'label' => 'Срок достижения 1.6 года малыша (для кормящих мама)'],
        ['id' => 6, 'label' => 'Срок истечение прописки'],
        ['id' => 7, 'label' => 'Лицензия'],
        ['id' => 8, 'label' => 'Разрешение на работу'],
        ['id' => 9, 'label' => 'Регистрационный лист'],
        ['id' => 10, 'label' => 'Справка о судимости'],
        ['id' => 11, 'label' => 'Медицинская справка'],
    ];
}

<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserRole::factory()->create([
            'label' => 'Пользователь',
            'slug' => 'user'
        ]);

        UserRole::factory()->create([
            'label' => 'Администратор',
            'slug' => 'admin'
        ]);

        User::factory()->create([
            'login' => 'admin',
            'name' => 'Администратор',
            'password' => Hash::make('!23qwe'),
            'user_role_id' => 2
        ]);

        Division::factory()->create([
            'label' => "Акушерский дистанционный консультативный центр"
        ]);
        Division::factory()->create([
            'label' => "Акушерское обсервационное отделение"
        ]);
        Division::factory()->create([
            'label' => "Акушерское отделение"
        ]);
        Division::factory()->create([
            'label' => "Акушерское отделение патологии беременности"
        ]);
        Division::factory()->create([
            'label' => "Аптека готовых лекарственных форм"
        ]);
        Division::factory()->create([
            'label' => "Бактериологическая лаборатория"
        ]);
        Division::factory()->create([
            'label' => "Бухгалтерия"
        ]);
        Division::factory()->create([
            'label' => "Гараж"
        ]);
        Division::factory()->create([
            'label' => "Гастроэнтерологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Гематологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Гинекологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Дистанционно-консультативный отдел по мониторингу больных пневмониями"
        ]);
        Division::factory()->create([
            'label' => "Женская консультация"
        ]);
        Division::factory()->create([
            'label' => "Кабинет рентген-ударноволнового дистанционного дробления камней почек"
        ]);
        Division::factory()->create([
            'label' => "Кабинет рентгенохирургических методов диагностики и лечения"
        ]);
        Division::factory()->create([
            'label' => "Кабинет трансфузионной терапии"
        ]);
        Division::factory()->create([
            'label' => "Кабинет централизованного обезболивания"
        ]);
        Division::factory()->create([
            'label' => "Канцелярия"
        ]);
        Division::factory()->create([
            'label' => "Кардиологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Кардиологическое отделение (РСЦ)"
        ]);
        Division::factory()->create([
            'label' => "Клинико-диагностическая лаборатория"
        ]);
        Division::factory()->create([
            'label' => "Консультативный центр РАО"
        ]);
        Division::factory()->create([
            'label' => "Мобильная бригада"
        ]);
        Division::factory()->create([
            'label' => "Неврологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Неврологическое отделение (РСЦ)"
        ]);
        Division::factory()->create([
            'label' => "Нейрохирургическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Нефрологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Областная консультативно-диагностическая поликлиника"
        ]);
        Division::factory()->create([
            'label' => "Ожоговое отделение"
        ]);
        Division::factory()->create([
            'label' => "Операционный блок"
        ]);
        Division::factory()->create([
            'label' => "Организационно-методический отдел"
        ]);
        Division::factory()->create([
            'label' => "Организационно-правовой отдел"
        ]);
        Division::factory()->create([
            'label' => "ОСП \"Благовещенская центральная районная поликлиника\""
        ]);
        Division::factory()->create([
            'label' => "ОСП \"ВА с.Волково\""
        ]);
        Division::factory()->create([
            'label' => "ОСП \"ВА с.Марково\""
        ]);
        Division::factory()->create([
            'label' => "ОСП \"ВА с.Новопетровка\""
        ]);
        Division::factory()->create([
            'label' => "ОСП \"ВА с.Усть-Ивановка\""
        ]);
        Division::factory()->create([
            'label' => "Отдел автоматизированных систем управления и телемедицинских технологий"
        ]);
        Division::factory()->create([
            'label' => "Отдел гражданской обороны и мобилизационной работы"
        ]);
        Division::factory()->create([
            'label' => "Отдел медицинской техники"
        ]);
        Division::factory()->create([
            'label' => "Отдел охраны труда"
        ]);
        Division::factory()->create([
            'label' => "Отдел размещения государственных заказов"
        ]);
        Division::factory()->create([
            'label' => "Отделение анестезиологии и реанимации"
        ]);
        Division::factory()->create([
            'label' => "Отделение анестезиологии и реанимации (ОПЦ)"
        ]);
        Division::factory()->create([
            'label' => "Отделение анестезиологии и реанимации (РАО)"
        ]);
        Division::factory()->create([
            'label' => "Отделение вспомогательных репродуктивных технологий"
        ]);
        Division::factory()->create([
            'label' => "Отделение гипербарической оксигенации"
        ]);
        Division::factory()->create([
            'label' => "Отделение клинико-экспертной работы"
        ]);
        Division::factory()->create([
            'label' => "Отделение клинической фармакологии"
        ]);
        Division::factory()->create([
            'label' => "Отделение медицинской реабилитации"
        ]);
        Division::factory()->create([
            'label' => "Отделение медицинской статистики"
        ]);
        Division::factory()->create([
            'label' => "Отделение новорожденных"
        ]);
        Division::factory()->create([
            'label' => "Отделение паллиативной медицинской помощи взрослым"
        ]);
        Division::factory()->create([
            'label' => "Отделение патологии новорожденных и недоношенных детей"
        ]);
        Division::factory()->create([
            'label' => "Отделение платных услуг"
        ]);
        Division::factory()->create([
            'label' => "Отделение ранней медицинской реабилитации"
        ]);
        Division::factory()->create([
            'label' => "Отделение сосудистой хирургии"
        ]);
        Division::factory()->create([
            'label' => "Отделение ультразвуковой диагностики"
        ]);
        Division::factory()->create([
            'label' => "Отделение ультразвуковой диагностики (ОПЦ)"
        ]);
        Division::factory()->create([
            'label' => "Отделение функциональной диагностики"
        ]);
        Division::factory()->create([
            'label' => "Отделение хирургическое торакальное"
        ]);
        Division::factory()->create([
            'label' => "Отделение челюстно-лицевой хирургии"
        ]);
        Division::factory()->create([
            'label' => "Оториноларингологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Патологоанатомическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Педиатрическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Пищеблок"
        ]);
        Division::factory()->create([
            'label' => "Планово-экономический отдел"
        ]);
        Division::factory()->create([
            'label' => "Прачечная"
        ]);
        Division::factory()->create([
            'label' => "Приемное отделение"
        ]);
        Division::factory()->create([
            'label' => "ПРИТ ПС ОАР ОПЦ ЦАР"
        ]);
        Division::factory()->create([
            'label' => "Проктологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Пульмонологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Ревматологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Рентгенологическое отделение (стационар)"
        ]);
        Division::factory()->create([
            'label' => "Территориальный центр медицины катастроф"
        ]);
        Division::factory()->create([
            'label' => "Технический отдел"
        ]);
        Division::factory()->create([
            'label' => "Травматологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Урологическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Физиотерапевтическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Хирургическое отделение"
        ]);
        Division::factory()->create([
            'label' => "Хозяйственный отдел"
        ]);
        Division::factory()->create([
            'label' => "Центр охраны здоровья семьи и репродукции"
        ]);
        Division::factory()->create([
            'label' => "Централизованное стерилизационное отделение"
        ]);
        Division::factory()->create([
            'label' => "Централизованный молочный блок"
        ]);
        Division::factory()->create([
            'label' => "Эндоскопическое отделение"
        ]);
    }
}

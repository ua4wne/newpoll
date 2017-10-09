<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создаем роли
        $admin = $auth->createRole('admin');
        $market = $auth->createRole('market');
        $manager = $auth->createRole('manager');
        $poll = $auth->createRole('poll');
        $energy = $auth->createRole('energy');
        $guard = $auth->createRole('guard');
        //$factor = $auth->createRole('factor');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($market);
        $auth->add($manager);
        $auth->add($poll);
        $auth->add($energy);
        $auth->add($guard);
        //$auth->add($factor);

        // Создаем разрешения.
        $adminTask = $auth->createPermission('adminTask');
        $adminTask->description = 'Задачи администратора';

        $viewDocument = $auth->createPermission('viewDocument');
        $viewDocument->description = 'Просмотр документов';

        $viewReport = $auth->createPermission('viewReport');
        $viewReport->description = 'Просмотр отчетов';

        $viewContact = $auth->createPermission('viewContact');
        $viewContact->description = 'Просмотр контактов';

        $viewForm = $auth->createPermission('viewForm');
        $viewForm->description = 'Просмотр анкет';

        $energyTask = $auth->createPermission('energyTask');
        $energyTask->description = 'Задачи энергетика';

        $guardTask = $auth->createPermission('guardTask');
        $guardTask->description = 'Задачи охраны';

        $marketTask = $auth->createPermission('marketTask');
        $marketTask->description = 'Задачи маркетолога';

        // Запишем эти разрешения в БД
        $auth->add($adminTask);
        $auth->add($viewDocument);
        $auth->add($viewReport);
        $auth->add($viewContact);
        $auth->add($viewForm);
        $auth->add($energyTask);
        $auth->add($guardTask);
        $auth->add($marketTask);

        // Теперь добавим наследования.

        // Роли «маркетолог» присваиваем разрешение «Редактирование анкет»
        $auth->addChild($market,$marketTask);
        $auth->addChild($market,$viewReport);
        $auth->addChild($poll,$viewForm);
        $auth->addChild($market,$poll);
        $auth->addChild($market,$manager);

        $auth->addChild($manager,$viewDocument);
        $auth->addChild($manager,$viewReport);
        $auth->addChild($manager,$viewContact);

        $auth->addChild($energy,$manager);
        $auth->addChild($energy,$energyTask);

        $auth->addChild($guard,$manager);
        $auth->addChild($guard,$guardTask);

        // админ наследует все роли. Он же админ, должен уметь всё! :D
        $auth->addChild($admin, $market);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $energy);
        $auth->addChild($admin, $guard);

        // Еще админ имеет собственное разрешение - «Просмотр админки»
        $auth->addChild($admin, $adminTask);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);

        // Назначаем роль editor пользователю с ID 2
        //$auth->assign($market, 2);
    }
}


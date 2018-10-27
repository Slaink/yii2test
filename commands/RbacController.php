<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $create = $auth->createPermission('createDriver');
        $create->description = 'Добавить водителя';
        $auth->add($create);

        $update = $auth->createPermission('updateDriver');
        $update->description = 'Редактировать водитея';
        $auth->add($update);

        $delete = $auth->createPermission('deleteDriver');
        $delete->description = 'Удалить водитея';
        $auth->add($delete);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $create);
        $auth->addChild($admin, $update);

        $super_admin = $auth->createRole('super_admin');
        $auth->add($super_admin);
        $auth->addChild($super_admin, $delete);
        $auth->addChild($super_admin, $admin);

        $auth->assign($super_admin, 2);
        $auth->assign($admin, 1);
    }
}
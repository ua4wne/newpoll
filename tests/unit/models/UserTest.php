<?php
namespace tests\models;
use app\modules\user\models\User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = \app\modules\user\models\User::findIdentity(100));
        expect($user->username)->equals('admin');

        expect_not(\app\modules\user\models\User::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        expect_that($user = \app\modules\user\models\User::findIdentityByAccessToken('100-token'));
        expect($user->username)->equals('admin');

        expect_not(\app\modules\user\models\User::findIdentityByAccessToken('non-existing'));
    }

    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('admin'));
        expect_not(\app\modules\user\models\User::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = \app\modules\user\models\User::findByUsername('admin');
        expect_that($user->validateAuthKey('test100key'));
        expect_not($user->validateAuthKey('test102key'));

        expect_that($user->validatePassword('admin'));
        expect_not($user->validatePassword('123456'));        
    }

}

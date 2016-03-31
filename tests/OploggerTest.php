<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OploggerTest extends TestCase
{

    use DatabaseMigrations, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }

    /**
     * @test
     */
    public function it_is_correctly_installed()
    {
        $this->assertInstanceOf(Protechstudio\Oplogger\Oplogger::class, Oplogger::getFacadeRoot());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_key_is_not_found()
    {
        $this->setExpectedException(Protechstudio\Oplogger\Exceptions\OploggerKeyNotFoundException::class);
        $user = factory(\App\User::class)->create();
        $this->be($user);
        Oplogger::write('undefined_key');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_no_user_is_provided_and_user_is_not_logged_in()
    {
        $this->setExpectedException(Protechstudio\Oplogger\Exceptions\OploggerUserNotLoggedException::class);
        Oplogger::write('test');
    }

    /**
     * @test
     */
    public function it_writes_a_successful_operation_log_using_a_logged_in_user()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        Oplogger::write('test');
        $this->seeInDatabase('logs', ['user_id' => $user->id, 'operation' => config('oplogger.types.test')]);
    }

    /**
     * @test
     */
    public function it_writes_a_successful_operation_log_providing_a_specific_user()
    {
        $user = factory(\App\User::class)->create();
        Oplogger::write('test', [], $user->id);
        $this->seeInDatabase('logs', ['user_id' => $user->id, 'operation' => config('oplogger.types.test')]);
    }
}

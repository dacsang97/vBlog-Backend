<?php


class AuthControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSignInWithCorrectEmailAndPasswordShouldReturnTTLAndToken()
    {
        $this->json('POST', 'auth/sign_in', [
            'email' => 'dacsang97@gmail.com',
            'password' => '123456',
        ])->seeJsonStructure([
            'ttl',
            'token',
        ]);
    }
}

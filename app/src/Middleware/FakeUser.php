<?php
namespace Project\Middleware;
use Fei\Service\Connect\Common\Entity\User;
use ObjectivePHP\Application\ApplicationInterface;

class FakeUser
{
    /**
     * Instantiate Response
     *
     * @param ApplicationInterface $app
     */
    public function __invoke(ApplicationInterface $app)
    {
        $fakeUser = new User();
        $fakeUser->setCurrentRole('ADMIN')
                 ->setEmail('devs@flash-global.net')
                 ->setFirstName('Dev')
                 ->setLastName('Dev')
                 ->setUserName('Dev');
        $app->getServicesFactory()->get('connect.client')->setUser($fakeUser);
    }
}

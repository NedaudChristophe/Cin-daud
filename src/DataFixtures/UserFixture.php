<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPassword('$2y$13$ZrTmy95p5HwIh5Ky5coun.8sHy/xDzjv3f3DNL.2wUL14QYw8JILu');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        
        
        $user1 = new User();
        $user1->setEmail('groot@root.com');
        $user1->setPassword('$2y$13$XYTprHYphuAvFrImAdnw5OEjccgiJOMClp/TA8uoGfBA.o6tIACha');
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);
        
        $user2= new User();
        $user2->setEmail('broot@broot.com');
        $user2->setPassword('$2y$13$6YOhnBvFzhl6CU02L9Y.3ub9KDLh8h34lIkS6vtpGxgTbtZYPKisy');
        $user2->setRoles(['ROLE_MANAGER']);
        $manager->persist($user2);
        
        $user3= new User();
        $user3->setEmail('');
        $user3->setPassword('');
        $user3->setRoles(['PUBLIC_ACCESS']);
        $manager->persist($user3);
        
        $manager->flush();



        

    }



    


    /**
     * Nous permet de classer les fixtures pour pouvoir les éxecuter séparement
     *
     * @return array
     */
    public static function getGroups(): array
     {
         return ['userGroup'];
     }
}

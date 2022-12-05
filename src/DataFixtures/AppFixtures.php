<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Offer;
use App\Entity\User;
use App\Entity\Wish;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    private $slugger;

    /**
     * Construct used for dependency injection 
     *
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            'temporaire',
            'permanent'
        ];

        $users = [];
        for ($i = 0; $i < 10; $i++) { 
            $user = new User();
            $user->setEmail('otroc'. $i . '@oclock.io');
            $user->setPassword('$2y$13$KZqO0hqpmYtiMYAEJMKKVefvwf4D/GcIGuT6TQHj7xFX7kl71BKFa');
            $user->setRoles(['ROLE_ADMIN', 'ROLE_MANAGER']);
            $user->setAlias('User' . $i);
            $user->setFirstname('firstname' . $i);
            $user->setLastname('lastname' . $i);
            $user->setZipcode('33600');
            $user->setPicture('https://upload.wikimedia.org/wikipedia/commons/1/1e/Michel_Sardou_2014.jpg');
            $user->setCreatedAt(new DateTime());
            
            $manager->persist($user);
            $users[] = $user;
        }
        
        $offers = [];
        for ($i = 0; $i <= 20; $i++) { 
            $offer = new Offer();
            $offer->setTitle('Offre #' . $i);
            $offer->setDescription('Description de l\'offre #' . $i);
            $offer->setZipcode('33000');
            $offer->setType($types[rand(0, 1)]);
            $offer->setCreatedAt(new DateTime());
            $offer->setUser($users[rand(0, count($users)-1)]);

            $manager->persist($offer);
            $offers[] = $offer;
        }

        $wishes = [];
        for ($i = 0; $i <= 20; $i++) { 
            $wish = new Wish();
            $wish->setTitle('Demande #' . $i);
            $wish->setDescription('Description de la demande #' . $i);
            $wish->setZipcode('33000');
            $wish->setType($types[rand(0, 1)]);
            $wish->setCreatedAt(new DateTime());
            $wish->setUser($users[rand(0, count($users)-1)]);

            $manager->persist($wish);
            $wishes[] = $wish;
        }
        
        $categories = [];
        for ($i = 0; $i <= 15; $i++) { 
            $category = new Category();
            $category->setName('La belle catégorie' . $i);
            $category->setSlug($this->slugger->slug($category->getName()));
            $category->setPicture('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Michael_Youn_2018.jpg/220px-Michael_Youn_2018.jpg');
            $category->setCreatedAt(new DateTime());

            $manager->persist($category);
            $categories[] = $category;
        }

        $manager->flush();
    }
}

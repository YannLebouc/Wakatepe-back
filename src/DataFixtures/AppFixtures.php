<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\MainCategory;
use App\Entity\Offer;
use App\Entity\User;
use App\Entity\Wish;
use App\Services\CustomSlugger;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    private $slugger;

    /**
     * Undocumented function
     *
     * @param MySlugger $slugger
     */
    public function __construct(CustomSlugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        // $types = [
        //     'temporaire',
        //     'permanent'
        // ];

        // $users = [];
        // for ($i = 0; $i < 10; $i++) {
        //     $user = new User();
        //     $user->setEmail('otroc' . $i . '@oclock.io');
        //     $user->setPassword('$2y$13$KZqO0hqpmYtiMYAEJMKKVefvwf4D/GcIGuT6TQHj7xFX7kl71BKFa');
        //     $user->setRoles(['ROLE_ADMIN', 'ROLE_MANAGER']);
        //     $user->setAlias('User' . $i);
        //     $user->setFirstname('firstname' . $i);
        //     $user->setLastname('lastname' . $i);
        //     $user->setZipcode('33600');
        //     $user->setPicture('https://upload.wikimedia.org/wikipedia/commons/1/1e/Michel_Sardou_2014.jpg');
        //     $user->setCreatedAt(new DateTime());

        //     $manager->persist($user);
        //     $users[] = $user;
        // }

        // $offers = [];
        // for ($i = 0; $i <= 30; $i++) {
        //     $offer = new Offer();
        //     $offer->setTitle('Offre #' . $i);
        //     $offer->setDescription('Description de l\'offre #' . $i);
        //     $offer->setZipcode('33000');
        //     $offer->setType($types[rand(0, 1)]);
        //     $offer->setCreatedAt(new DateTime());
        //     $offer->setUser($users[rand(0, count($users) - 1)]);

        //     $manager->persist($offer);
        //     $offers[] = $offer;
        // }

        // $wishes = [];
        // for ($i = 0; $i <= 30; $i++) {
        //     $wish = new Wish();
        //     $wish->setTitle('Demande #' . $i);
        //     $wish->setDescription('Description de la demande #' . $i);
        //     $wish->setZipcode('33000');
        //     $wish->setType($types[rand(0, 1)]);
        //     $wish->setCreatedAt(new DateTime());
        //     $wish->setUser($users[rand(0, count($users) - 1)]);

        //     $manager->persist($wish);
        //     $wishes[] = $wish;
        // }

        $mainCategoriesName = ['Maison', 'Mode', 'Multimédia', 'Loisirs', 'Divers'];
        $mainCategories = [];
        for ($i = 0; $i < count($mainCategoriesName); $i++) {
            $mainCategory = new MainCategory();
            $mainCategory->setName($mainCategoriesName[$i]);
            $mainCategory->setSlug($this->slugger->slugToLower($mainCategory->getName()));
            $mainCategory->setCreatedAt(new DateTime());

            $manager->persist($mainCategory);
            $mainCategories[] = $mainCategory;
        }

        $allCategories =
            [
                '0' =>
                [
                    'Aménagement',
                    'Electroménager',
                    'Décoration',
                    'Bricolage',
                    'Jardinage',
                    'Arts de la table'
                ],
                '1' =>
                [
                    'Vêtements',
                    'Chaussures',
                    'Accessoires et bagagerie',
                    'Montres et bijoux',
                    'Equipements bébé',
                    'Vêtements bébé'
                ],
                '2' =>
                [
                    'Informatique',
                    'Consoles et jeux vidéo',
                    'Images et sons',
                    'Téléphonie'
                ],
                '3' =>
                [
                    'DVD-Films',
                    'CD-Musique',
                    'Livres',
                    'Vélos',
                    'Sports et hobbies',
                    'Instruments de musique',
                    'Collections',
                    'Jeux et jouets',
                    'Vins et gastronomie'
                ],
                '4' =>
                [
                    'Autres'
                ]
            ];


        $categories = [];
        foreach ($allCategories as $cat => $catNames) {
            for ($i = 0; $i < count($catNames); $i++) {
                $category = new Category();
                $category->setName($catNames[$i]);
                $category->setSlug($this->slugger->slugToLower($category->getName()));
                $category->setPicture('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Michael_Youn_2018.jpg/220px-Michael_Youn_2018.jpg');
                $category->setCreatedAt(new DateTime());
                $category->setMainCategory($mainCategories[$cat]);

                // for ($j = 0; $j < 2; $j++) {
                //     $offersInCategory = [];
                //     $wishesInCategory = [];
                //     $newOffer = $offers[rand(0, count($offers) - 1)];
                //     $newWish = $wishes[rand(0, count($wishes) - 1)];

                //     if (!in_array($newOffer, $offersInCategory)) {
                //         $category->addOffer($newOffer);
                //         $offersInCategory[] = $newOffer;
                //     }
                //     if (!in_array($newWish, $wishesInCategory)) {
                //         $category->addWish($newWish);
                //         $wishesInCategory[] = $newWish;
                //     }

                $manager->persist($category);
                $categories[] = $category;
            }
        }

        $manager->flush();
    }
}

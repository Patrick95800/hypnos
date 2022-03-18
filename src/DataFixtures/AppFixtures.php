<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Suite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->addUsers($manager);
        $this->addHotels($manager);
        $this->addSuites($manager);
        $this->addBookings($manager);

        $manager->flush();
    }

    public function addUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Patrick');
        $user->setLastname('Barros');
        $user->setEmail('patrick.barros@hypnos.com');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'patpat');
        $user->setPassword($hashedPassword);
        $user->setRoles([
            User::ROLE_ADMIN
        ]);
        $manager->persist($user);

        $user2 = new User();
        $user2->setFirstname('Jonathan');
        $user2->setLastname('Pierrot');
        $user2->setEmail('jonathan.pierrot@hypnos.com');
        $hashedPassword = $this->passwordHasher->hashPassword($user2, 'joejoe');
        $user2->setPassword($hashedPassword);
        $user2->setRoles([
            User::ROLE_MANAGER
        ]);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setFirstname('Thomas');
        $user3->setLastname('Carpentier');
        $user3->setEmail('thomas.carpentier@hypnos.com');
        $hashedPassword = $this->passwordHasher->hashPassword($user3, 'toto');
        $user3->setPassword($hashedPassword);
        $user3->setRoles([
            User::ROLE_MANAGER
        ]);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setFirstname('Isabelle');
        $user4->setLastname('Durand');
        $user4->setEmail('isabelle.durand@yahoo.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user4, 'isabelledu75');
        $user4->setPassword($hashedPassword);
        $manager->persist($user4);

        $manager->flush();
    }

    public function addHotels(ObjectManager $manager): void
    {
        $user2 = $manager->getRepository(User::class)->findOneByEmail('jonathan.pierrot@hypnos.com');
        $user3 = $manager->getRepository(User::class)->findOneByEmail('thomas.carpentier@hypnos.com');

        $hotel = new Hotel();
        $hotel->setName('Victoria Palace Hotel');
        $hotel->setDescription('Le Victoria Palace Hotel se situe à 250 mètres de la rue du Cherche-Midi datant du XVIIIème siècle, ainsi qu’à 750 mètres de la gare Montparnasse et du grand magasin Le Bon Marché. Une connexion Wi-Fi est disponible gratuitement dans l’ensemble de l’établissement.');
        $hotel->setAddress('6 Rue Blaise Desgoffe, 6e arr., 75006 Paris, France');
        $hotel->setCity('Paris');
        $hotel->setOwner($user2);
        $user2->setHotel($hotel);
        $manager->persist($hotel);

        $hotel2 = new Hotel();
        $hotel2->setName('Hotel West-End');
        $hotel2->setDescription('Cet hôtel de caractère de luxe se situe en plein cœur de Paris, près de l’Avenue des Champs-Elysées. Il présente une décoration élégante et un mobilier unique.');
        $hotel2->setAddress(' 7 rue Clément Marot, 8e arr., 75008 Paris, France');
        $hotel2->setCity('Paris');
        $hotel2->setOwner($user3);
        $user3->setHotel($hotel2);
        $manager->persist($hotel2);

        $manager->flush();
    }

    public function addSuites(ObjectManager $manager): void
    {
        $hotel = $manager->getRepository(Hotel::class)->findOneByName('Victoria Palace Hotel');
        $hotel2 = $manager->getRepository(Hotel::class)->findOneByName('Hotel West-End');

        $suite = new Suite();
        $suite->setTitle('Chambre Familiale Supérieure');
        $suite->setDescription('Cette chambre familiale insonorisée dispose d’un plateau/bouilloire et d’un minibar. Elle comprend également un terrarium, un Google Chromecast, une tablette avec accès à des journaux internationaux en ligne, une machine à café et des articles de toilette gratuits.');
        $suite->setPrice(13500);
        $suite->setBookingLink('https://www.booking.com/hotel/fr/victoria-palace.fr.html');
        $hotel->addSuite($suite);
        $suite->setHotel($hotel);
        $manager->persist($suite);

        $suite2 = new Suite();
        $suite2->setTitle('Chambre Double Standard');
        $suite2->setDescription('Cette chambre est équipée d’une station d’accueil pour iPod, d’une télévision à écran LCD et d’un minibar. Climatisée, elle dispose également d’une salle de bains en marbre. ');
        $suite2->setPrice(12500);
        $suite2->setBookingLink('https://www.booking.com/hotel/fr/hotelwestend.fr.html');
        $hotel2->addSuite($suite2);
        $suite2->setHotel($hotel2);
        $manager->persist($suite2);

        $suite3 = new Suite();
        $suite3->setTitle('Suite Junior');
        $suite3->setDescription('Cette suite dispose d’un coin salon, de la climatisation et d’un minibar. La salle de bains privative comprend une baignoire, un peignoir et des chaussons. Vous y trouverez également une station d’accueil pour iPod.');
        $suite3->setPrice(32400);
        $suite3->setBookingLink('https://www.booking.com/hotel/fr/hotelwestend.fr.html');
        $hotel2->addSuite($suite3);
        $suite3->setHotel($hotel2);
        $manager->persist($suite3);

        $manager->flush();
    }

    public function addBookings(ObjectManager $manager): void
    {
        $user4 = $manager->getRepository(User::class)->findOneByEmail('isabelle.durand@yahoo.fr');
        $hotel = $manager->getRepository(Hotel::class)->findOneByName('Victoria Palace Hotel');
        $hotel2 = $manager->getRepository(Hotel::class)->findOneByName('Hotel West-End');
        $suite = $manager->getRepository(Suite::class)->findOneBy([
            'title' => 'Chambre Familiale Supérieure',
            'hotel' => $hotel
        ]);
        $suite2 = $manager->getRepository(Suite::class)->findOneBy([
            'title' => 'Chambre Double Standard',
            'hotel' => $hotel2
        ]);

        $booking = new Booking();
        $booking->setHotel($hotel);
        $booking->setSuite($suite);
        $booking->setStatus(Booking::STATUS_ACCEPTED);
        $booking->setBeginAt(new \DateTimeImmutable('2020-02-13'));
        $booking->setEndAt(new \DateTimeImmutable('2020-02-15'));
        $booking->setUser($user4);
        $user4->addBooking($booking);
        $manager->persist($booking);

        $booking2 = new Booking();
        $booking2->setHotel($hotel);
        $booking2->setSuite($suite);
        $booking2->setStatus(Booking::STATUS_CANCELLED);
        $booking2->setBeginAt(new \DateTimeImmutable('2021-05-20'));
        $booking2->setEndAt(new \DateTimeImmutable('2021-05-22'));
        $booking2->setUser($user4);
        $user4->addBooking($booking2);
        $manager->persist($booking2);

        $booking3 = new Booking();
        $booking3->setHotel($hotel2);
        $booking3->setSuite($suite2);
        $booking3->setStatus(Booking::STATUS_DECLINED);
        $booking3->setBeginAt(new \DateTimeImmutable('2021-12-24'));
        $booking3->setEndAt(new \DateTimeImmutable('2021-12-25'));
        $booking3->setUser($user4);
        $user4->addBooking($booking3);
        $manager->persist($booking3);

        $manager->flush();
    }
}

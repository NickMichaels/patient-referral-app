<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Provider;
use App\Entity\Practicioner;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private \Faker\Generator $faker;

    /**
     * @var array<string> $specialties
     */
    private array $specialties;

    public function __construct()
    {
        $this->faker = Factory::create();

        $this->specialties = [
            'Dermatologist',
            'Anesthesiologist',
            'Cardiologist',
            'Oncologist',
            'Internal medicine',
            'Immunologist',
            'Neurologist',
        ];
    }

    /**
     * Create some users
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function createUsers(ObjectManager $manager): void
    {
        // This is just to make an initial ROLE_ADMIN and a ROLE_USER USER
        $user = new User();
        $user->setEmail("testuser@example.com");
        $user->setRoles(["ROLE_USER"]);
        // You can run php bin/console security:hash-password
        // and follow the prompts to hash your own pw
        $user->setPassword('$2y$13$LLqUUzwjwDFgRF5FFfNtIeMVmD6WEFiCVcojdKaN4tOYTHKCT1xTC');
        $manager->persist($user);

        $user = new User();
        // $user->setEmail("your_email_here@example.com");
        $user->setEmail("nick+7@nick.com");
        $user->setRoles(["ROLE_USER"]);
        //$user->setPassword('your_hashed_pw_here');
        $user->setPassword('$2y$13$LLqUUzwjwDFgRF5FFfNtIeMVmD6WEFiCVcojdKaN4tOYTHKCT1xTC');
        $manager->persist($user);

        $manager->flush();
    }

    /**
     * Create some providers
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function createProviders(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $provider = new Provider();
            $provider->setName($this->faker->company);
            $provider->setAddressLine1($this->faker->streetAddress);
            $provider->setCity($this->faker->city);
            $provider->setState($this->faker->state);
            $provider->setZip(intval($this->faker->postcode));
            $provider->setEmail($this->faker->email);
            $provider->setPhone($this->faker->phoneNumber);
            $manager->persist($provider);
        }

        $manager->flush();
    }

    /**
     * Create some practicioners
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function createPracticioner(ObjectManager $manager): void
    {
        $jobTitles = [
            'Doctor',
            'Registered Nurse',
            'Licensed Practical Nurse',
            'Physical Therapist',
            'Nursing Assistant',
            'Physician Assistant',
            'Radiologic Technologist',
            'Phlebotomist',
            'Dietitian',
        ];
        for ($i = 0; $i < 10; $i++) {
            $practicioner = new Practicioner();

            $practicioner->setName($this->faker->firstname . " "  . $this->faker->lastName);
            $jobTitle = $jobTitles[array_rand($jobTitles)];
            $practicioner->setJobTitle($jobTitle);
            // If we have a doctor, set a specialty
            if ($jobTitle == 'Doctor') {
                $practicioner->setSpecialty($this->specialties[array_rand($this->specialties)]);
            }
            $practicioner->setLicenseNumber($this->faker->randomNumber);
            $practicioner->setEmail($this->faker->email);
            $practicioner->setPhone($this->faker->phoneNumber);

            $manager->persist($practicioner);
        }

        $manager->flush();
    }

    /**
     * Create some patients
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function createPatients(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $patient = new Patient();

            $patient->setName($this->faker->firstname . " "  . $this->faker->lastName);

            // Defining this as json allows us to add ot this later
            // and as this is supposed to a small project I dont want
            // to get too much in the weeds on details
            $patientData = [
                'accountNumber' => $this->faker->randomNumber(8),
                'DOB' => $this->faker->date,
                'address' => $this->faker->address,
            ];
            $patient->setData($patientData);

            $patient->setEmail($this->faker->email);
            $patient->setPhone($this->faker->phoneNumber);
            $manager->persist($patient);
        }

        $manager->flush();
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $this->createProviders($manager);
        $this->createPracticioner($manager);
        $this->createPatients($manager);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager,
UserPasswordEncoderInterface $encoder)
    {
//        A quel objet relier le form ? User
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
//        champs du $user ci-dessus ainsi reliés/bindés aux champs du form.

//        form doit analyser la request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            Encoder le mdp avant de persister
//            On passe $user car instance de User, et dans Secu => bcrypt pour User !
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user); // préparer sauvegarde user ds bdd
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
   }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }
}

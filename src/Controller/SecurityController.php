<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager)
    {
//        A quel objet relier le form ? User
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
//        champs du $user ci-dessus ainsi reliés/bindés aux champs du form.

//        form doit analyser la request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user); // préparer sauvegarde user ds bdd
            $manager->flush();
        }

        return $this->render('security/registration.html.twig', array(
            'form' => $form->createView()
        ));
   }
}

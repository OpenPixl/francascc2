<?php

namespace App\Controller\Admin;

use App\Entity\Admin\User;
use App\Form\Admin\ResettingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class resettingController
 * @package App\Controller\Admin
 * @Route("/op_admin/security/resetting", name="op_admin_security")
 */
class resettingController extends AbstractController
{
    /**
     * @Route("/", name="_resetting")
     */
    public function resetting(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(ResettingType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "Votre mot de passe a été renouvelé.");

            return $this->redirectToRoute('op_webapp_college_espcoll', [
                'iduser' => $user->getId(),
            ]);

        }

        return $this->render('admin/resetting/request.html.twig', [
            'form' => $form->createView(),
            'user' => $user

        ]);

    }

    /**
     * @Route("/{id}", name="_resetting_member")
     */
    public function resetting_member(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $form = $this->createForm(ResettingType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "Votre mot de passe a été renouvelé.");

            return $this->redirectToRoute('op_webapp_college_espcoll', [
                'iduser' => $user->getId(),
            ]);

        }

        return $this->render('admin/resetting/request2.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

    }
}

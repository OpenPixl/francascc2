<?php

namespace App\Controller\Webapp;

use App\Entity\Admin\College;
use App\Entity\Webapp\Message;
use App\Form\Webapp\ReplyType;
use App\Form\Webapp\MessageType;
use App\Repository\Webapp\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webapp/message", name="op_webapp_message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        return $this->render('webapp/message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
            'college' => $college
        ]);
    }

    /**
     * @Route("/new", name="_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        // code d'information du college en cours d'utilisation
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        $message = new Message();
        $message -> setAuthor($user);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_message_messagesbyuser', ['iduser' => $user->getId()]);
        }

        return $this->render('webapp/message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
            'college' => $college
        ]);
    }

    /**
     * @Route("/{id}", name="_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        // code pour afficher la bannière de l'établissement en haut de page
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        // code pour basculer le message en statut lu
        $read = $message->getIsRead();
        if ($read == 0) {
            $message->setIsRead(1);
            $this->getDoctrine()->getManager()->flush();

        }

        return $this->render('webapp/message/show.html.twig', [
            'message' => $message,
            'college' => $college
        ]);
    }

    /**
     * @Route("/{id}/edit", name="_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_message_messagesbyuser', ['iduser' => $user->getId()]);
        }

        return $this->render('webapp/message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_webapp_message_messagesbyuser', ['iduser' => $user->getId()]);
    }

    /**
     * @Route("/messagesbyuser/{iduser}", name="_messagesbyuser", methods={"GET"})
     */
    public function listMessageByUser($iduser): Response
    {
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        $messages = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            ->listMessagesByUser($iduser)
        ;

        return $this->render('webapp/message/index.html.twig',[
            'messages' => $messages,
            'college' => $college

        ]);
    }

    /**
     * Action de réponse à un mail
     * @Route("/reply_mail/{id}", name="_reply_mail")
     */
    public function reply_mail(Message $message, Request $request){
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        $reply = clone $message;
        $reply
            ->setSubject("Rép : " . $message->getSubject())
            ->addRecipient($message->getAuthor())
            ->setAuthor($user)
            ->setContent($message->getContent())
        ;

        $replyform = $this->createForm(ReplyType::class, $reply);
        $replyform->handleRequest($request);

        if ($replyform->isSubmitted() && $replyform->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reply);
            $em->flush();

            return $this->redirectToRoute('op_webapp_message_messagesbyuser', ['iduser' => $user->getId()]);
        }

        return $this->render('webapp/message/reply_mail.html.twig',[
            'college'=> $college,
            'message'=> $message,
            'reply' => $reply,
            'replyform' => $replyform->createView()
        ]);
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\ReplyMessageModel;
use AppBundle\Form\Model\StartConversationModel;
use AppBundle\Form\Type\ReplyMessageType;
use AppBundle\Form\Type\StartConversationType;
use FOS\Message\Model\MessageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class DefaultController extends Controller
{
    const MESSAGES_PER_PAGE = 2;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/messages/conversations", name="messages_conversations")
     */
    public function conversationsAction()
    {
        $conversations = $this->get('fos_message.repository')->getPersonConversations($this->getUser());

        return $this->render('messages/conversations.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * @Route("/messages/start", name="messages_start")
     */
    public function startAction(Request $request)
    {
        $model = new StartConversationModel();

        $form = $this->createForm(StartConversationType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation = $this->get('fos_message.sender')->startConversation(
                $this->getUser(),
                $model->getRecipients(),
                $model->getBody(),
                $model->getSubject()
            );

            return $this->redirectToRoute('messages_conversation', [
                'id' => $conversation->getId(),
                'page' => 1
            ]);
        }

        return $this->render('messages/start.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/messages/{id}/{page}", name="messages_conversation")
     */
    public function conversationAction(Request $request, $id, $page)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->get('fos_message.repository');

        // Find the conversation
        $conversation = $repository->getConversation($id);

        if (! $conversation) {
            throw $this->createNotFoundException();
        }

        if (! $conversation->isPersonInConversation($this->getUser())) {
            throw $this->createAccessDeniedException();
        }

        // Retrieve the messages of this page
        $total = $conversation->getMessages()->count();
        $offset = ($page - 1) * self::MESSAGES_PER_PAGE;

        if ($offset >= $total) {
            throw $this->createNotFoundException();
        }

        $totalPages = ceil($total / self::MESSAGES_PER_PAGE);

        /** @var MessageInterface[] $messages */
        $messages = $repository->getMessages($conversation, $offset, self::MESSAGES_PER_PAGE);

        foreach ($messages as $message) {
            $messagePerson = $message->getMessagePerson($this->getUser());

            if (! $messagePerson->isRead()) {
                $messagePerson->setRead();
                $manager->persist($messagePerson);
            }
        }

        $manager->flush();

        // Reply form
        $model = new ReplyMessageModel();

        $replyForm = $this->createForm(ReplyMessageType::class, $model);
        $replyForm->handleRequest($request);

        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
            $message = $this->get('fos_message.sender')->sendMessage(
                $conversation,
                $this->getUser(),
                $model->getBody()
            );

            // Go to last page and last message
            $lastPage = ceil(($total + 1) / self::MESSAGES_PER_PAGE);

            $url = $this->generateUrl('messages_conversation', [
                'id' => $conversation->getId(),
                'page' => $lastPage
            ]);

            $url .= '#message-' . $message->getId();

            return $this->redirect($url);
        }

        return $this->render('messages/conversation.html.twig', [
            'conversation' => $conversation,
            'messages' => $messages,
            'total' => $total,
            'totalPages' => $totalPages,
            'page' => $page,
            'replyForm' => $replyForm->createView(),
        ]);
    }
}

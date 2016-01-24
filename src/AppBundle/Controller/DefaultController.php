<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\StartConversationModel;
use AppBundle\Form\Type\StartConversationType;
use FOS\Message\Driver\Doctrine\ORM\Entity\Conversation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/messages", name="messages_conversations")
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
    public function conversationAction($id, $page)
    {
        $conversation = $this->get('fos_message.repository')->getConversation($id);

        if (! $conversation) {
            throw $this->createNotFoundException();
        }

        if (! $conversation->isPersonInConversation($this->getUser())) {
            throw $this->createAccessDeniedException();
        }

        $messages = $this->get('fos_message.repository')->getMessages($conversation, ($page - 1) * 20, 20);

        return $this->render('messages/conversation.html.twig', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }
}

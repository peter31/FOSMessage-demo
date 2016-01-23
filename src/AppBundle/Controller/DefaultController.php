<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function startAction()
    {
        /** @todo */
    }

    /**
     * @Route("/messages/{id}/{page}", name="messages_conversation")
     */
    public function conversationAction()
    {
        /** @todo */
    }
}

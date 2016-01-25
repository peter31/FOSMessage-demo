<?php

namespace AppBundle\Twig;

use AppBundle\Controller\DefaultController;
use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\PersonInterface;
use FOS\Message\RepositoryInterface;

class MessagesExtension extends \Twig_Extension
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('members_list', [ $this, 'membersList' ]),
            new \Twig_SimpleFunction('get_message_page', [ $this, 'messagePage' ]),
            new \Twig_SimpleFunction('count_unread_conversations', [ $this, 'countUnread' ]),
        );
    }

    public function membersList(ConversationInterface $conversation, PersonInterface $differentFrom = null)
    {
        $otherMembersUsernames = [];

        foreach ($conversation->getConversationPersons() as $conversationPerson) {
            if ($conversationPerson->getPerson()->getId() != $differentFrom->getId()) {
                $otherMembersUsernames[] = $conversationPerson->getPerson()->getUsername();
            }
        }

        return implode(', ', $otherMembersUsernames);
    }

    public function messagePage(ConversationInterface $conversation, MessageInterface $message)
    {
        $position = $conversation->getMessages()->indexOf($message);

        return ceil(($position + 1) / DefaultController::MESSAGES_PER_PAGE);
    }

    public function countUnread(PersonInterface $person)
    {
        $conversations = $this->repository->getPersonConversations($person);
        $count = 0;

        foreach ($conversations as $conversation) {
            if ($conversation->getFirstUnreadMessage($person)) {
                $count++;
            }
        }

        return $count;
    }

    public function getName()
    {
        return 'messages';
    }
}

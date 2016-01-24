<?php

namespace AppBundle\Twig;

use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\PersonInterface;

class ConversationMembersExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('members_list', [ $this, 'membersList' ]),
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

    public function getName()
    {
        return 'conversation_mmebers';
    }
}

<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof \App\Entity\Article;
    }

    protected function voteOnAttribute($attribute, $article, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($article->getAuthor() == null) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
                case 'EDIT':
            if (($article->getAuthor()->getId() === $user->getId()) ||
                ('ROLE_ADMIN' === $user->getRoles()[0])){
                return true;
            }
                
            case 'DELETE':
               if (($article->getAuthor()->getId() === $user->getId()) ||
                ('ROLE_ADMIN' === $user->getRoles()[0])){
                return true;
            }
        }

        return false;
    }
}

<?php

namespace App\Security\Voter;

use DateTime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const EDIT = 'MOVIE_EDIT_1400';
    public const DELETE = 'MOVIE_DELETE_1200';

    /**
     * Est ce que je participe au vote ?
     *
     * @param string $attribute la question : POST_EDIT
     * @param mixed $subject sur quelle entité : Movie
     * @return boolean Oui ou non je participe
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        //? le voter de Symfony pour les roles va checker si $attribute commence par ROLE_
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Movie;
    }

    /**
     * Je participe au vote
     *
     * @param string $attribute la question à laquelle je dois répondre : POST_EDIT
     * @param mixed $subject sur quelle entité : Movie
     * @param TokenInterface $token les informations du contexte, l'utilisateur
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
       // if (!$user instanceof UserInterface) {
       //     return false;
      //  }
        // Maurice n'a pas le droit de modifier parce qu'il fait toujours des conneries ! ^^
      //  if ($user->getUserIdentifier() == "maurice@fait_des_betises.com")
      //  {
       //     return false;
     //   }
        // ... (check conditions and return true to grant permission) ...
      //  switch ($attribute) {
          //  case self::EDIT:
                // logic to determine if the user can EDIT
                // si il est plus de 14h00 on n'a pas le droit de modifier un film
                /**
                 * @link https://www.php.net/manual/fr/datetime.format.php
                 * H	Heure, au format 24h, avec les zéros initiaux	00 à 23
                 * i	Minutes avec les zéros initiaux	00 à 59
                 */
                
              //  $laDateDuJour= new DateTime('now');
                //dd($laDateDuJour);
               // if (date_format($laDateDuJour, 'Hi') > 1345)
              //  {
               //     return false;
              //  }
                
                // il est moins de 14h00
             //   return true;

           //     break;
         //   case self::DELETE:
          //      $laDateDuJour= new DateTime('now');
                //dd($laDateDuJour);
           //     if (date_format($laDateDuJour, 'Hi') > 1200)
           //     {
           //         return false;
             //   }
            //    return true;
             //   break;
       // }

       // return false;
   // }
    }
}
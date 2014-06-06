<?php
namespace Itsallagile\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Itsallagile\CoreBundle\Document\Team;

class UserRepository extends DocumentRepository
{
    /**
     * Find all users not in a Team
     *
     * @param Team $team
     * @return array
     */
    public function findByNotInTeam(Team $team)
    {
        // Create array of user IDs
        $teamUserIDs = array();
        foreach($team->getUsers() as $user) {
            $teamUserIDs[] = new \MongoId($user->getId());
        }

        // Find the Users who don't have those ID
        $users = $this->createQueryBuilder()
            ->field('_id')->notIn($teamUserIDs)
            ->sort('name', 'ASC')
            ->getQuery()
            ->execute();

        return $users;
    }
}
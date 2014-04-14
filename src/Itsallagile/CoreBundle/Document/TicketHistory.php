<?php

namespace Itsallagile\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as JMS;

/**
 * @MongoDB\EmbeddedDocument
 */
class TicketHistory
{
    /**
     * @MongoDB\Id
     * @JMS\Exclude
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="User")
     * @JMS\Accessor(getter="getUserName")
     */
    private $user;

    /**
     * @MongoDB\Field(type="string")
     */
    private $field;

    /**
     * @MongoDB\Field(type="string")
     */
    private $oldValue;

    /**
     * @MongoDB\Field(type="string")
     */
    private $newValue;

    /**
     * @MongoDB\Field(type="date")
     */
    private $created;

    /**
     * Create a new ticket history document
     *
     * @param User $user
     * @param string $field
     * @param string $oldValue
     * @param string $newValue
     */
    public function __construct(User $user, $field, $oldValue, $newValue)
    {
        $this->user = $user;
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
        $this->created = new \DateTime();
    }

    /**
     * Get the full name of the user
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user->getFullName();
    }
}

<?php

namespace Itsallagile\CoreBundle\EventListener;

use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Itsallagile\CoreBundle\Document\Ticket;
use Itsallagile\CoreBundle\Document\TicketHistory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TicketUpdateHistory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $updateFields = array('status', 'content');

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Add a history record to the ticket when some fields changed
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        // Get the document
        $document = $args->getObject();

        // Check it's a Ticket as we don't care about anything else here
        if ($document instanceof Ticket) {
            // Find out which fields we care about have changed
            $changes = $args->getDocumentChangeSet();
            $changedFields = array_intersect($this->updateFields, array_keys($changes));

            if (count($changedFields) > 0) {
                // Get the user
                $user = $this->container->get('security.context')->getToken()->getUser();

                foreach ($changedFields as $field) {
                    // Create the history record
                    $oldValue = $args->getOldValue($field);
                    $newValue = $args->getNewValue($field);
                    $history = new TicketHistory($user, $field, $oldValue, $newValue);
                    $document->addHistory($history);
                }

                // Recompute changes
                $dm = $args->getObjectManager();
                $uow = $dm->getUnitOfWork();
                $meta = $dm->getClassMetadata(get_class($document));
                $uow->recomputeSingleDocumentChangeSet($meta, $document);
            }
        }
    }

    /**
     * Get the array of fields we check for update
     *
     * @return array
     */
    public function getUpdateFields()
    {
        return $this->updateFields;
    }
}
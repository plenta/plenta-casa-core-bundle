<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\NotificationCenter\Gateway;

use Contao\Input;
use Plenta\CasaCoreBundle\Model\LeadModel;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Terminal42\NotificationCenterBundle\BulkyItem\BulkyItemStorage;
use Terminal42\NotificationCenterBundle\Gateway\AbstractGateway;
use Terminal42\NotificationCenterBundle\Parcel\Parcel;
use Terminal42\NotificationCenterBundle\Parcel\Stamp\BulkyItemsStamp;
use Terminal42\NotificationCenterBundle\Parcel\Stamp\TokenCollectionStamp;
use Terminal42\NotificationCenterBundle\Receipt\Receipt;
use Terminal42\NotificationCenterBundle\Token\TokenCollection;

class LeadGateway extends AbstractGateway
{
    public const string NAME = 'plentacasa_lead';

    public function __construct(
        private readonly BulkyItemStorage $bulkyItemStorage,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    protected function doSealParcel(Parcel $parcel): Parcel
    {
        $tokens = $parcel->getStamp(TokenCollectionStamp::class)->tokenCollection;

        return $parcel->withStamp(
            $this->createLeadStamp($tokens, $parcel->getStamp(BulkyItemsStamp::class))
        );
    }

    protected function doSendParcel(Parcel $parcel): Receipt
    {
        return Receipt::createForSuccessfulDelivery($parcel);
    }

    protected function createLeadStamp(TokenCollection $tokens, ?BulkyItemsStamp $bulkyItems): ?LeadModel
    {
        $object = ObjectModel::findPublishedByIdOrAlias(
            Input::get('auto_item', false, true)
        );

        if (null !== $object) {
            $lead = new LeadModel();
            $lead->tstamp = time();
            $lead->object = $object->id;
            $lead->save();

            return $lead;
        }

        return null;
    }
}

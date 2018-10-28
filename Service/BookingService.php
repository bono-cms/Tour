<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Service;

use Krystal\Stdlib\VirtualEntity;
use Krystal\Date\TimeHelper;
use Cms\Service\AbstractManager;
use Tour\Storage\TourBookingMapperInterface;

final class BookingService extends AbstractManager
{
    /**
     * Any compliant tour booking mapper
     * 
     * @var \Tour\Storage\TourBookingMapperInterface
     */
    private $tourBookingMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourBookingMapperInterface $tourBookingMapper
     * @return void
     */
    public function __construct(TourBookingMapperInterface $tourBookingMapper)
    {
        $this->tourBookingMapper = $tourBookingMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $booking = new VirtualEntity();
        $booking->setId($row['id'])
                ->setTour($row['tour'])
                ->setClient($row['client'])
                ->setEmail($row['email'])
                ->setPhone($row['phone'])
                ->setDatetime($row['datetime'])
                ->setAmount($row['amount']);

        return $booking;
    }

    /**
     * Returns last ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourBookingMapper->getMaxId();
    }

    /**
     * Fetch booking by its ID
     * 
     * @param int $id
     * @return mixed
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->tourBookingMapper->findByPk($id));
    }

    /**
     * Fetch all bookings
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->tourBookingMapper->fetchAll());
    }

    /**
     * Deletes booking by its ID
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourBookingMapper->deleteByPk($id);
    }

    /**
     * Save booking
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        if (!$input['id']) {
            $input['datetime'] = TimeHelper::getNow();
        }

        return $this->tourBookingMapper->persist($input);
    }
}

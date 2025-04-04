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
use Krystal\Text\TextUtils;
use Cms\Service\AbstractManager;
use Tour\Storage\TourBookingMapperInterface;
use Tour\Storage\TourBookingGuestMapperInterface;

final class BookingService extends AbstractManager
{
    /**
     * Any compliant tour booking mapper
     * 
     * @var \Tour\Storage\TourBookingMapperInterface
     */
    private $tourBookingMapper;

    /**
     * Compliant mapper
     * 
     * @var Tour\Storage\MySQL\TourBookingGuestMapperInterface
     */
    private $tourBookingGuestMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourBookingMapperInterface $tourBookingMapper
     * @param \Tour\Storage\MySQL\TourBookingGuestMapperInterface $tourBookingGuestMapper
     * @return void
     */
    public function __construct(TourBookingMapperInterface $tourBookingMapper, TourBookingGuestMapperInterface $tourBookingGuestMapper)
    {
        $this->tourBookingMapper = $tourBookingMapper;
        $this->tourBookingGuestMapper = $tourBookingGuestMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $booking = new VirtualEntity();
        $booking->setId($row['id'])
                ->setStatus($row['status'])
                ->setTour($row['tour'])
                ->setClient($row['client'])
                ->setEmail($row['email'])
                ->setPhone($row['phone'])
                ->setDatetime($row['datetime'])
                ->setAmount($row['amount'])
                ->setToken($row['token']);

        if (isset($row['start'], $row['end'])) {
            $booking->setStart($row['start'])
                    ->setEnd($row['end']);
        }

        return $booking;
    }

    /**
     * Returns prepared pagination instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->tourBookingMapper->getPaginator();
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
     * Confirms that payment is done by token
     * 
     * @param string $token
     * @return boolean
     */
    public function confirmPayment($token)
    {
        return $this->tourBookingMapper->updateStatusByToken($token, 1);
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
     * Finds row by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token)
    {
        return $this->prepareResult($this->tourBookingMapper->findByToken($token));
    }

    /**
     * Fetch all bookings
     * 
     * @param int $page Current page number
     * @param int $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($page, $itemsPerPage)
    {
        return $this->prepareResults($this->tourBookingMapper->fetchAll($page, $itemsPerPage));
    }

    /**
     * Fetch all guests
     * 
     * @param int $id
     * @return array
     */
    public function fetchGuests($id)
    {
        return $this->tourBookingGuestMapper->fetchAll($id);
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
     * Delete many bookings by their associated IDs
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids)
    {
        foreach ($ids as $id) {
            $this->deleteById($id);
        }

        return true;
    }

    /**
     * Creates a new booking (purely used on a site)
     * 
     * @param array $input
     * @param array $guests Optional guests
     * @return boolean
     */
    public function book(array $input, array $guests = [])
    {
        $input['datetime'] = TimeHelper::getNow();
        $input['status'] = -1; // Means temporary
        $input['token'] = TextUtils::uniqueString();

        $this->tourBookingMapper->persist($input);

        if ($guests) {
            $this->tourBookingGuestMapper->store($this->getLastId(), $guests);
        }

        return true;
    }

    /**
     * Save booking
     * 
     * @param array $input
     * @return string
     */
    public function save(array $input)
    {
        // Unset CAPTCHA if present
        if (isset($input['captcha'])) {
            unset($input['captcha']);
        }

        if (!$input['id']) {
            $input['datetime'] = TimeHelper::getNow();
            $input['status'] = -1; // Temporary
            $input['token'] = TextUtils::uniqueString();
        }

        $this->tourBookingMapper->persist($input);

        return $input['token'];
    }
}

<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\View;

use Krystal\Widget\Bootstrap5\Accordion\AccordionMaker;

final class DayAccordion
{
    /**
     * Renders accordion
     * 
     * @param array $days An array of day entities
     * @param array $options Accordion options
     * @return string Rendered accordion
     */
    public static function render(array $days, array $options = [])
    {
        $items = [];

        foreach ($days as $day) {
            $items[] = [
                'header' => $day->getTitle(), 
                'body' => $day->getDescription()
            ];
        }

        $accordion = new AccordionMaker($items, $options);
        return $accordion->render();
    }
}

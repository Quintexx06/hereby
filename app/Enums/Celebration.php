<?php

namespace App\Enums;

/**
 * When the wedding is celebrated. Sets the preset times in the programme step.
 */
enum Celebration: string
{
    case Day = 'day';
    case Evening = 'evening';
    case DayAndEvening = 'day_and_evening';
}

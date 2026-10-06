<?php

namespace App\Enums;

/**
 * Kinds of events within a wedding. Swiss weddings often split the civil
 * ceremony and the celebration across days and guest lists.
 */
enum EventType: string
{
    case CivilCeremony = 'civil_ceremony';
    case Ceremony = 'ceremony';
    case Reception = 'reception';
    case Dinner = 'dinner';
    case Party = 'party';
    case Brunch = 'brunch';
    case Other = 'other';
}

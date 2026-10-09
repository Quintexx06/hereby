<?php

namespace App\Enums;

/**
 * The things guests ask the couple about. One block of each per wedding.
 * Travel and stay (roadmap 1.8) share the content model with the rest (1.2).
 */
enum ContentBlockType: string
{
    case Story = 'story';
    case Venue = 'venue';
    case DressCode = 'dress_code';
    case Faq = 'faq';
    case Travel = 'travel';
    case Stay = 'stay';
}

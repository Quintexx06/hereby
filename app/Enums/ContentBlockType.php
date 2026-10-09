<?php

namespace App\Enums;

/**
 * The four things guests ask the couple about. One block of each per wedding.
 */
enum ContentBlockType: string
{
    case Story = 'story';
    case Venue = 'venue';
    case DressCode = 'dress_code';
    case Faq = 'faq';
}

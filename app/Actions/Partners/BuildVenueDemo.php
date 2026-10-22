<?php

namespace App\Actions\Partners;

use App\Enums\ContentBlockType;
use App\Enums\Locale;
use App\Enums\ResponseStatus;
use App\Enums\WeddingTemplate;
use App\Models\ContentBlock;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Partner;
use App\Support\StockPreviewPhoto;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;

/**
 * A venue's sales demo: the tour preview of Anna & Luca's wedding there,
 * made to look like a site a couple really sends. A guest household
 * instead of the bride, our photos while the venue has none, menus to
 * choose, and a story, dress code and FAQ around the venue's own texts.
 * The sample texts make no claims about the venue. Nothing is stored.
 */
class BuildVenueDemo
{
    public function __construct(private BuildTourPreview $preview) {}

    /**
     * @param  list<array{event_id: int, status: string, menu?: string|null}>|null  $answers  What the visitor just sent, shown once and never saved.
     */
    public function handle(Partner $venue, WeddingTemplate $template, ?array $answers = null): Household
    {
        $household = $this->preview->handle($venue, [
            'first' => 'Anna',
            'second' => 'Luca',
            'date' => self::exampleDate()->toDateString(),
            'template' => $template->value,
        ]);
        $wedding = $household->wedding;
        $wedding->menu_options = [['key' => 'fleisch', 'label' => 'Fleisch'], ['key' => 'fisch', 'label' => 'Fisch'], ['key' => 'vegi', 'label' => 'Vegetarisch']];
        $wedding->offers_stay = true;

        $household->name = 'Familie Keller';
        $household->setRelation('guests', new Collection([$this->guest($answers ?? [])]));
        $household->forceFill(['responded_at' => $answers === null ? null : now()]);

        if ($wedding->couplePhotos->isEmpty()) {
            $wedding->setRelation('couplePhotos', new Collection(array_map(StockPreviewPhoto::make(...), StockPreviewPhoto::filesFor($template))));
        }

        $wedding->setRelation('contentBlocks', new Collection([
            $this->block(ContentBlockType::Story, [
                'body' => 'Ein verpasster Zug, ein geteiltes Taxi und eine Diskussion über die beste Pizza der Stadt. So hat es angefangen.',
                'items' => [
                    ['question' => '2018 · Bern', 'answer' => 'Der letzte Zug war weg. Anna und Luca teilten sich ein Taxi und waren sich nur in einem einig: Sie wollten sich wiedersehen.'],
                    ['question' => '2021 · Die erste Wohnung', 'answer' => 'Zwei Zimmer, ein Balkon und ein Sofa, das nicht durch die Tür passte.'],
                    ['question' => '2025 · Der Antrag', 'answer' => 'Auf einer Wanderung, bei Sonnenaufgang. Sie hat Ja gesagt, bevor er fertig war.'],
                ],
            ]),
            ...$wedding->contentBlocks->all(),
            $this->block(ContentBlockType::DressCode, [
                'body' => 'Festlich und sommerlich. Der Apéro ist draussen, also lieber Blockabsätze als Stilettos.',
            ]),
            $this->block(ContentBlockType::Faq, [
                'items' => [
                    ['question' => 'Sind Kinder willkommen?', 'answer' => 'Sehr. Für die Kleinen gibt es ein eigenes Menü.'],
                    ['question' => 'Was wünscht ihr euch?', 'answer' => 'Dass ihr dabei seid. Wer mag, darf etwas an unsere Hochzeitsreise beitragen.'],
                    ['question' => 'Bis wann sollen wir antworten?', 'answer' => 'Bis zum Datum oben. Ihr könnt eure Antwort bis dahin jederzeit ändern.'],
                ],
            ]),
        ]));

        return $household;
    }

    /**
     * The demo's one guest, with the answers just sent, if any.
     *
     * @param  list<array{event_id: int, status: string, menu?: string|null}>  $answers
     */
    private function guest(array $answers): Guest
    {
        $guest = (new Guest(['first_name' => 'Sandra']))->forceFill(['id' => 1]);

        return $guest->setRelation('responses', new Collection(array_map(fn (array $answer): EventResponse => new EventResponse([
            'event_id' => $answer['event_id'],
            'status' => ResponseStatus::from($answer['status']),
            'menu_choice' => $answer['menu'] ?? null,
        ]), $answers)));
    }

    /**
     * A Saturday in June, at least four months ahead: a believable date.
     */
    public static function exampleDate(): CarbonImmutable
    {
        $june = CarbonImmutable::now('Europe/Zurich')->setDate((int) now()->format('Y'), 6, 1)->startOfDay();

        if ($june->lt(now()->addMonths(4))) {
            $june = $june->addYear();
        }

        return $june->next(CarbonImmutable::SATURDAY)->addWeeks(2);
    }

    /**
     * @param  array{body?: string, items?: list<array{question: string, answer: string}>}  $text
     */
    private function block(ContentBlockType $type, array $text): ContentBlock
    {
        return (new ContentBlock([
            'type' => $type,
            'content' => [Locale::GermanSwiss->value => ['title' => null, 'body' => null, 'items' => [], ...$text]],
        ]))->forceFill(['id' => crc32('demo-'.$type->value)]);
    }
}

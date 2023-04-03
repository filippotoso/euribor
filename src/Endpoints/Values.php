<?php

namespace FilippoToso\Euribor\Endpoints;

use DateTime;
use FilippoToso\Api\Sdk\Endpoint;
use Symfony\Component\DomCrawler\Crawler;
use FilippoToso\Euribor\Euribor;
use FilippoToso\Euribor\Payloads\Value;

class Values extends Endpoint
{
    protected const TYPE_MAPPING = [
        'Euribor 1 week' => Euribor::EURIBOR_1_WEEK,
        'Euribor 1 month' => Euribor::EURIBOR_1_MONTH,
        'Euribor 3 months' => Euribor::EURIBOR_3_MONTHS,
        'Euribor 6 months' => Euribor::EURIBOR_6_MONTHS,
        'Euribor 12 months' => Euribor::EURIBOR_12_MONTHS,
    ];

    public function read()
    {
        $response = $this->get('/');

        $crawler = new Crawler($response->body());

        $dates = $crawler->filter('.TableResponsive thead tr th')
            ->each(function (Crawler $node, $i) {
                return $node->text();
            });

        $dates = array_values(array_filter($dates));
        $dates = array_map(function ($date) {
            return DateTime::createFromFormat('m/d/Y', $date);
        }, $dates);

        $count = $crawler->filter('.TableResponsive tbody tr th')->count();

        $results = [];

        for ($id = 0; $id < $count; $id++) {
            $current = $this->parseRow($crawler, $id, $dates);
            $results = array_merge($results, $current);
        }

        return $results;
    }

    protected function parseRow($crawler, $id, $dates)
    {
        $type = $crawler->filter('.TableResponsive tbody tr')
            ->eq($id)
            ->filter('th')
            ->text();
        $type = static::TYPE_MAPPING[$type];

        $values = $crawler->filter('.TableResponsive tbody tr')
            ->eq(0)
            ->filter('td')
            ->each(function (Crawler $node, $i) {
                return $node->text();
            });

        $values = array_map(function ($value) {
            return (float)str_replace(',', '.', trim($value, " \t\n\r\0\x0B%"));
        }, $values);

        $results = [];

        for ($i = 0; $i < count($dates); $i++) {
            $results[$type][] = Value::make([
                'date' => $dates[$i],
                'value' => $values[$i],
            ]);
        }

        return $results;
    }
}

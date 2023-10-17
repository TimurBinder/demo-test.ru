<?php

namespace Redsign\DevFunc\Controller;

use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Web\Uri;

class Location extends Controller
{
    protected function init()
    {
        parent::init();
    }

    public function configureActions()
    {
        return [
            'setLocation' => [
                'prefilters' => [
                    new Csrf()
                ]
            ]
        ];
    }

    public function setLocationAction(string $locationId): array
    {
        $locationId = (int) $locationId;

        \Redsign\DevFunc\Sale\Location\Location::setMyCity($locationId);
        $result['id'] = $locationId;

        $arRegions = \Redsign\DevFunc\Sale\Location\Region::getRegions();
        $arRegionCurrent = [];

        if (is_array($arRegions) && count($arRegions) > 0) {
            $context = Context::getCurrent();
            $server = $context->getServer();

            foreach ($arRegions as $arRegion) {
                if ($locationId == $arRegion['LOCATION_ID']) {
                    $arRegionCurrent = $arRegion;
                    break;
                }
            }
            unset($arRegion);

            if (empty($arRegionCurrent))
                $arRegionCurrent = \Redsign\DevFunc\Sale\Location\Region::getDefaultRegion();

            if (!empty($arRegionCurrent)) {
                \Redsign\DevFunc\Sale\Location\Region::set($arRegionCurrent);

                if (
                    is_array($arRegionCurrent['LIST_DOMAINS']) && count($arRegionCurrent['LIST_DOMAINS']) > 0
                    && !in_array($server->getServerName(), $arRegionCurrent['LIST_DOMAINS'])
                ) {
                    /** @var string $backurl */
                    $backurl = $this->request->get('backurl') ?: '/';
                    $uri = new Uri($backurl);
                    $uri->setHost(reset($arRegionCurrent['LIST_DOMAINS']));
                    $result['redirect'] = $uri->getUri();
                }
            }
        }

        return $result;
    }
}

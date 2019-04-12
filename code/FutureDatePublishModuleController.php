<?php

class FutureDatePublishModuleController extends ContentController
{


    public function init()
    {
        parent::init();


    }


    public function index(SS_HTTPRequest $request) {
        return $this->getViewer('index')->process($this);
    }

    public function Menu($level) {
        $items = parent::Menu($level);

        $isDraftPreview = 'Stage' === $this->request->getVar('stage');
        if ($isDraftPreview) {
            return $items;
        }

        $now = strtotime('now');
        $visible = array();
        foreach ($items as $page) {
            if ($page->PublishDate && strtotime($page->PublishDate) <= $now) {
                $visible[] = $page;
            } elseif (!$page->PublishDate) {
                $visible[] = $page;
            }
        }

        return new ArrayList($visible);
    }



}

<?php

class FuturePublishDate extends DataExtension
{
    private static $db = array(
        'PublishDate' => 'SS_DateTime'
    );

    public function updateCMSFields(FieldList $fields) {
        $datetimeField = new DatetimeField( 'PublishDate', 'Publish From' );

        $dateField = $datetimeField->getDateField();
        $dateField->setConfig( 'dateformat', 'yyyy-MM-dd' );
        $dateField->setConfig( 'showcalendar', true );

        $timeField = $datetimeField->getTimeField();
        $timeField->setConfig( 'timeformat', 'H:m:s' );

        $fields->insertBefore( $datetimeField, 'Content' );
    }

    public function populateDefaults() {
        $this->owner->PublishDate = SS_Datetime::now();
    }
}

class FuturePublishDateController extends Extension
{
    public function beforeCallActionHandler($request, $action) {
        if ('index' !== $action || $this->owner->is_a('ErrorPage_Controller')) {
            return;
        }

        $isDraftPreview = 'Stage' === $request->getVar('stage');

        if( !$isDraftPreview
            && $this->owner->PublishDate
            && strtotime($this->owner->PublishDate) > strtotime('now')
        ){
            // bug in SS 3.1 in OldPageRedirector
            // $this->owner->httpError( 404 );

            $response = $request->isMedia() ? null : ErrorPage::response_for(404);
            if ($response) {
                return $response;
            }

            throw new SS_HTTPResponse_Exception('404 Not Found', 404);
        }
    }
}
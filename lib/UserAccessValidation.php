<?php
include_once('./lib/Pager.php');
include_once('./lib/ListEditor.php');
include_once('./lib/EmailTemplates.php');
include_once('./lib/Attachments.php');
include_once('./lib/JobOrders.php');
include_once('./lib/Contacts.php');


/**
 *  Companies Library
 *  @package    CATS
 *  @subpackage Library
 */
class UserAccessValidation
{
    private $_db;
    private $_siteID;

    public $extraFields;


    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
        $this->extraFields = new ExtraFields($siteID, DATA_ITEM_COMPANY);
    }


}

?>
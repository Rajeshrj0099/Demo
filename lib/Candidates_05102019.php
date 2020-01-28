<?php
/**
 * CATS
 * Candidates Library
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 *
 * @package    CATS
 * @subpackage Library
 * @copyright Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * @version    $Id: Candidates.php 3813 2007-12-05 23:16:22Z brian $
 */

include_once('./lib/Attachments.php');
include_once('./lib/Pipelines.php');
include_once('./lib/History.php');
include_once('./lib/SavedLists.php');
include_once('./lib/ExtraFields.php');
include_once('lib/DataGrid.php');
include_once('./lib/EncryptDecrypt.php');

/**
 *  Candidates Library
 *  @package    CATS
 *  @subpackage Library
 */
class Candidates
{
    private $_db;
    private $_siteID;

    public $extraFields;
    

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
        $this->extraFields = new ExtraFields($siteID, DATA_ITEM_CANDIDATE);
    }

    /**
     * Adds a candidate to the database and returns its candidate ID.
     *
     * @param string First name.
     * @param string Middle name / initial.
     * @param string Last name.
     * @param string Primary e-mail address.
     * @param string Secondary e-mail address.
     * @param string Home phone number.
     * @param string Mobile phone number.
     * @param string Work phone number.
     * @param string Address (can be multiple lines).
     * @param string City.
     * @param string State / province.
     * @param string Postal code.
     * @param string Source where this candidate was found.
     * @param string Key skills.
     * @param string Date available.
     * @param string Current employer.
     * @param boolean Is this candidate willing to relocate?
     * @param string Current pay rate / salary.
     * @param string Desired pay rate / salary.
     * @param string Misc. candidate notes.
     * @param string Candidate's personal web site.
     * @param integer Entered-by user ID.
     * @param integer Owner user ID.
     * @param string EEO gender, or '' to not specify.
     * @param string EEO gender, or '' to not specify.
     * @param string EEO veteran status, or '' to not specify.
     * @param string EEO disability status, or '' to not specify.
     * @param boolean Skip creating a history entry?
     * @return integer Candidate ID of new candidate, or -1 on failure.          * @param string ref_recruiter 
     */
    public function add($firstName, $middleName, $lastName, $email1, $email2,
        $phoneHome, $phoneCell, $phoneWork, $address, $country, $city, $state, $zip,
        $source, $keySkills, $otherSkills, $dateAvailable, $currentEmployer, $canRelocate,
        $currentPay, $desiredPay, $notes, $webSite, $bestTimeToCall, $enteredBy, $owner,
        $ref_recruiter,$linkedin,$skypeid,$preflocations,$visa,$title,$totexp1,$totexp2,                $relexp1,$relexp2,$domain,$category,$roles,$education,$college,$graduation,$emptype,                $authorization,$courseone,$coursetwo,$coursethree,$suppliers,$resumeurl,                $gender = '', $race = '', $veteran = '', $disability = '',
                $skipHistory = false )
    {                $mask = new EncryptDecrypt();        $email1 = $mask ->encrypt($email1);        $phoneCell = $mask ->encrypt($phoneCell);        if(!isset($email2) || trim($email2) === '')        {            $email2 = '';        }        else        {            $email2 = $mask ->encrypt($email2);        }                if(!isset($phoneHome) || trim($phoneHome) === '')        {            $phoneHome = '';        }        else        {            $phoneHome = $mask ->encrypt($phoneHome);        }                if(!isset($phoneWork) || trim($phoneWork) === '')        {            $phoneWork = '';        }        else        {            $phoneWork = $mask ->encrypt($phoneWork);        }                        $sql = sprintf(                        "INSERT INTO candidate (                first_name,                middle_name,                last_name,                email1,                email2,                phone_home,                phone_cell,                phone_work,                address,                country,                city,                state,                zip,                source,                key_skills,                other_skills,                date_available,                current_employer,                can_relocate,                current_pay,                desired_pay,                notes,                web_site,                best_time_to_call,                entered_by,                is_hot,                is_prequalified,                owner,                site_id,                date_created,                date_modified,                ref_recruiter,                linkedin,                skypeid,                preflocations,                visa,                title,                totexp1,                totexp2,                relexp1,                relexp2,                domain,                category,                roles,                education,                college,                graduation,                emptype,                authorization,                courseone,                coursetwo,                coursethree,                suppliers,                resumeurl,                eeo_ethnic_type_id,                eeo_veteran_type_id,                eeo_disability_status,                eeo_gender                        )                        VALUES (                            %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                0,                0,                %s,                %s,                NOW(),                NOW(),                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s,                %s            )",                        $this->_db->makeQueryString($firstName),            $this->_db->makeQueryString($middleName),            $this->_db->makeQueryString($lastName),            $this->_db->makeQueryString($email1),            $this->_db->makeQueryString($email2),            $this->_db->makeQueryString($phoneHome),            $this->_db->makeQueryString($phoneCell),            $this->_db->makeQueryString($phoneWork),            $this->_db->makeQueryString($address),            $this->_db->makeQueryString($country),            $this->_db->makeQueryString($city),            $this->_db->makeQueryString($state),            $this->_db->makeQueryString($zip),            $this->_db->makeQueryString($source),            $this->_db->makeQueryString($keySkills),            $this->_db->makeQueryString($otherSkills),            $this->_db->makeQueryStringOrNULL($dateAvailable),            $this->_db->makeQueryString($currentEmployer),            ($canRelocate ? '1' : '0'),            $this->_db->makeQueryString($currentPay),            $this->_db->makeQueryString($desiredPay),            $this->_db->makeQueryString($notes),            $this->_db->makeQueryString($webSite),            $this->_db->makeQueryString($bestTimeToCall),            $this->_db->makeQueryInteger($enteredBy),            $this->_db->makeQueryInteger($owner),            $this->_siteID,                        $this->_db->makeQueryString($ref_recruiter),            $this->_db->makeQueryString($linkedin),            $this->_db->makeQueryString($skypeid),            $this->_db->makeQueryString($preflocations),            $this->_db->makeQueryInteger($visa),            $this->_db->makeQueryString($title),            $this->_db->makeQueryString($totexp1),            $this->_db->makeQueryString($totexp2),            $this->_db->makeQueryString($relexp1),            $this->_db->makeQueryString($relexp2),            $this->_db->makeQueryString($domain),            $this->_db->makeQueryString($category),            $this->_db->makeQueryString($roles),            $this->_db->makeQueryString($education),            $this->_db->makeQueryString($college),            $this->_db->makeQueryString($graduation),            $this->_db->makeQueryInteger($emptype),            $this->_db->makeQueryString($authorization),            $this->_db->makeQueryString($courseone),            $this->_db->makeQueryString($coursetwo),            $this->_db->makeQueryString($coursethree),            $this->_db->makeQueryString($suppliers),            $this->_db->makeQueryString($resumeurl),                                    $this->_db->makeQueryInteger($race),            $this->_db->makeQueryInteger($veteran),            $this->_db->makeQueryString($disability),            $this->_db->makeQueryString($gender)            );                        //echo $sql;        //exit;
        $queryResult = $this->_db->query($sql);
        if (!$queryResult)
        {
            return -1;
        }

        $candidateID = $this->_db->getLastInsertID();

        if (!$skipHistory)
        {
            $history = new History($this->_siteID);
            $history->storeHistoryNew(DATA_ITEM_CANDIDATE, $candidateID);
        }

        return $candidateID;
    }

    /**
     * Updates a candidate.
     *
     * @param integer Candidate ID to update.
     * @param string First name.
     * @param string Middle name / initial.
     * @param string Last name.
     * @param string Primary e-mail address.
     * @param string Secondary e-mail address.
     * @param string Home phone number.
     * @param string Mobile phone number.
     * @param string Work phone number.
     * @param string Address (can be multiple lines).
     * @param string City.
     * @param string State / province.
     * @param string Postal code.
     * @param string Source where this candidate was found.
     * @param string Key skills.
     * @param string Date available.
     * @param string Current employer.
     * @param boolean Is this candidate willing to relocate?
     * @param string Current pay rate / salary.
     * @param string Desired pay rate / salary.
     * @param string Misc. candidate notes.
     * @param string Candidate's personal web site.
     * @param integer Owner user ID.
     * @param string EEO gender, or '' to not specify.
     * @param string EEO gender, or '' to not specify.
     * @param string EEO veteran status, or '' to not specify.
     * @param string EEO disability status, or '' to not specify.
     * @param string ref_recruiter.               * @return boolean True if successful; false otherwise.
     */
    public function update($candidateID, $isActive, $firstName, $middleName, $lastName,
        $email1, $email2, $phoneHome, $phoneCell, $phoneWork, $address,
        $country, $city, $state, $zip, $source, $keySkills, $otherSkills, $dateAvailable,
        $currentEmployer, $canRelocate, $currentPay, $desiredPay,
        $notes, $webSite, $bestTimeToCall, $owner, $isHot,$isPrequalified, $email, $emailAddress, 
        $ref_recruiter,$linkedin,$skypeid,$preflocations,$visa,$title,$totexp1,$totexp2,$relexp1,$relexp2,                $domain,$category,$roles,$education,$college,$graduation,$emptype,$authorization,$courseone,                $coursetwo,$coursethree,$suppliers,$resumeurl,$gender = '', $race = '', $veteran = '', $disability = '')
    {                $mask = new EncryptDecrypt();        $email1 = $mask ->encrypt($email1);        $phoneCell = $mask ->encrypt($phoneCell);                        if(!isset($email2) || trim($email2) === '')        {            $email2 = '';        }        else         {            $email2 = $mask ->encrypt($email2);        }        if(!isset($phoneHome) || trim($phoneHome) === '')        {            $phoneHome = '';        }        else        {            $phoneHome = $mask ->encrypt($phoneHome);        }                if(!isset($phoneWork) || trim($phoneWork) === '')        {            $phoneWork = '';        }        else        {            $phoneWork = $mask ->encrypt($phoneWork);        }                                        $sql = sprintf(                        "UPDATE                            candidate                        SET                            is_active             = %s,                first_name            = %s,                middle_name           = %s,                last_name             = %s,                email1                = %s,                email2                = %s,                phone_home            = %s,                phone_work            = %s,                phone_cell            = %s,                address               = %s,                country               = %s,                city                  = %s,                state                 = %s,                zip                   = %s,                source                = %s,                key_skills            = %s,                other_skills          = %s,                date_available        = %s,                current_employer      = %s,                current_pay           = %s,                desired_pay           = %s,                can_relocate          = %s,                is_hot                = %s,                is_prequalified       = %s,                notes                 = %s,                web_site              = %s,                best_time_to_call     = %s,                owner                 = %s,                date_modified         = NOW(),                ref_recruiter         = %s,                linkedin     = %s,                skypeid    = %s,                preflocations    = %s,                visa    = %s,                title    = %s,                totexp1    = %s,                totexp2    = %s,                relexp1    = %s,                relexp2    = %s,                domain    = %s,                category    = %s,                roles    = %s,                education    = %s,                college    = %s,                graduation    = %s,                emptype    = %s,                authorization    = %s,                courseone    = %s,                coursetwo    = %s,                coursethree    = %s,                suppliers    = %s,                resumeurl   =%s,                eeo_ethnic_type_id    = %s,                eeo_veteran_type_id   = %s,                eeo_disability_status = %s,                eeo_gender            = %s                        WHERE                            candidate_id = %s                        AND                            site_id = %s",                        ($isActive ? '1' : '0'),            $this->_db->makeQueryString($firstName),            $this->_db->makeQueryString($middleName),            $this->_db->makeQueryString($lastName),            $this->_db->makeQueryString($email1),            $this->_db->makeQueryString($email2),            $this->_db->makeQueryString($phoneHome),            $this->_db->makeQueryString($phoneWork),            $this->_db->makeQueryString($phoneCell),            $this->_db->makeQueryString($address),            $this->_db->makeQueryString($country),            $this->_db->makeQueryString($city),            $this->_db->makeQueryString($state),            $this->_db->makeQueryString($zip),            $this->_db->makeQueryString($source),            $this->_db->makeQueryString($keySkills),            $this->_db->makeQueryString($otherSkills),            $this->_db->makeQueryStringOrNULL($dateAvailable),            $this->_db->makeQueryString($currentEmployer),            $this->_db->makeQueryString($currentPay),            $this->_db->makeQueryString($desiredPay),            ($canRelocate ? '1' : '0'),            ($isHot ? '1' : '0'),            ($isPrequalified ? '1' : '0'),            $this->_db->makeQueryString($notes),            $this->_db->makeQueryString($webSite),            $this->_db->makeQueryString($bestTimeToCall),            $this->_db->makeQueryInteger($owner),                        $this->_db->makeQueryString($ref_recruiter),            $this->_db->makeQueryString($linkedin),            $this->_db->makeQueryString($skypeid),            $this->_db->makeQueryString($preflocations),            $this->_db->makeQueryInteger($visa),            $this->_db->makeQueryString($title),            $this->_db->makeQueryString($totexp1),            $this->_db->makeQueryString($totexp2),            $this->_db->makeQueryString($relexp1),            $this->_db->makeQueryString($relexp2),            $this->_db->makeQueryString($domain),            $this->_db->makeQueryString($category),            $this->_db->makeQueryString($roles),            $this->_db->makeQueryString($education),            $this->_db->makeQueryString($college),            $this->_db->makeQueryString($graduation),            $this->_db->makeQueryInteger($emptype),            $this->_db->makeQueryString($authorization),            $this->_db->makeQueryString($courseone),            $this->_db->makeQueryString($coursetwo),            $this->_db->makeQueryString($coursethree),            $this->_db->makeQueryString($suppliers),            $this->_db->makeQueryString($resumeurl),            $this->_db->makeQueryInteger($race),            $this->_db->makeQueryInteger($veteran),            $this->_db->makeQueryString($disability),            $this->_db->makeQueryString($gender),            $this->_db->makeQueryInteger($candidateID),            $this->_siteID                                    );                        
        //echo 'email2:'.$email2.'<br>'.$sql;        //exit;        
        $preHistory = $this->get($candidateID);
        $queryResult = $this->_db->query($sql);
        $postHistory = $this->get($candidateID);

        $history = new History($this->_siteID);
        $history->storeHistoryChanges(
            DATA_ITEM_CANDIDATE, $candidateID, $preHistory, $postHistory
        );

        if (!$queryResult)
        {
            return false;
        }

        if (!empty($emailAddress))
        {
            /* Send e-mail notification. */
            //FIXME: Make subject configurable.
            $mailer = new Mailer($this->_siteID);
            $mailerStatus = $mailer->sendToOne(
                array($emailAddress, ''),
                'CATS Notification: Candidate Ownership Change',
                $email,
                true
            );
        }

        return true;
    }

    /**
     * Removes a candidate and all associated records from the system.
     *
     * @param integer Candidate ID to delete.
     * @return void
     */
    public function delete($candidateID)
    {
        /* Delete the candidate from candidate. */
        $sql = sprintf(
            "DELETE FROM
                candidate
            WHERE
                candidate_id = %s
            AND
                site_id = %s",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );
        $this->_db->query($sql);

        $history = new History($this->_siteID);
        $history->storeHistoryDeleted(DATA_ITEM_CANDIDATE, $candidateID);

        /* Delete pipeline entries from candidate_joborder. */
        $sql = sprintf(
            "DELETE FROM
                candidate_joborder
            WHERE
                candidate_id = %s
            AND
                site_id = %s",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );
        $this->_db->query($sql);

        /* Delete pipeline history from candidate_joborder_status_history. */
        $sql = sprintf(
            "DELETE FROM
                candidate_joborder_status_history
            WHERE
                candidate_id = %s
            AND
                site_id = %s",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );
        $this->_db->query($sql);

        /* Delete from saved lists. */
        $sql = sprintf(
            "DELETE FROM
                saved_list_entry
            WHERE
                data_item_id = %s
            AND
                site_id = %s
            AND
                data_item_type = %s",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID,
            DATA_ITEM_CANDIDATE
        );
        $this->_db->query($sql);

        /* Delete attachments. */
        $attachments = new Attachments($this->_siteID);
        $attachmentsRS = $attachments->getAll(
            DATA_ITEM_CANDIDATE, $candidateID
        );

        foreach ($attachmentsRS as $rowNumber => $row)
        {
            $attachments->delete($row['attachmentID']);
        }

        /* Delete extra fields. */
        $this->extraFields->deleteValueByDataItemID($candidateID);
    }

    /**
     * Returns all relevent candidate information for a given candidate ID.
     *
     * @param integer Candidate ID.
     * @return array Associative result set array of candidate data, or array()
     *               if no records were returned.
     */
    public function get($candidateID)
    {
    	$this->_siteID=1;
        $sql = sprintf(
            "SELECT
                candidate.candidate_id AS candidateID,
                candidate.is_active AS isActive,
                candidate.first_name AS firstName,
                candidate.middle_name AS middleName,
                candidate.last_name AS lastName,
                candidate.email1 AS email1,
                candidate.email2 AS email2,
                candidate.phone_home AS phoneHome,
                candidate.phone_work AS phoneWork,
                candidate.phone_cell AS phoneCell,
                candidate.address AS address,
                candidate.country AS country,
                candidate.city AS city,
                candidate.state AS state,
                candidate.zip AS zip,
                candidate.source AS source,
                candidate.key_skills AS keySkills,
                candidate.other_skills AS otherSkills,
                candidate.current_employer AS currentEmployer,
                candidate.current_pay AS currentPay,
                candidate.desired_pay AS desiredPay,
                candidate.notes AS notes,
                candidate.owner AS owner,
                candidate.can_relocate AS canRelocate,
                candidate.web_site AS webSite,
                candidate.best_time_to_call AS bestTimeToCall,
                candidate.is_hot AS isHot,
                candidate.is_prequalified AS isPrequalified,
                candidate.is_admin_hidden AS isAdminHidden,                DATE_FORMAT(
                    candidate.date_created, '%%m-%%d-%%y (%%h:%%i %%p)'
                ) AS dateCreated,
                DATE_FORMAT(
                    candidate.date_modified, '%%m-%%d-%%y (%%h:%%i %%p)'
                ) AS dateModified,
                COUNT(
                    candidate_joborder.joborder_id
                ) AS pipeline,
                (
                    SELECT
                        COUNT(*)
                    FROM
                        candidate_joborder_status_history
                    WHERE
                        candidate_id = %s
                    AND
                        status_to = %s
                    AND
                        site_id = %s
                ) AS submitted,
                CONCAT(
                    candidate.first_name, ' ', candidate.last_name
                ) AS candidateFullName,
                CONCAT(
                    entered_by_user.first_name, ' ', entered_by_user.last_name
                ) AS enteredByFullName,
                CONCAT(
                    owner_user.first_name, ' ', owner_user.last_name
                ) AS ownerFullName,
                owner_user.email AS owner_email,
                DATE_FORMAT(
                    candidate.date_available, '%%m-%%d-%%y'
                ) AS dateAvailable,                CONCAT(                    user.first_name, ' ', user.last_name                ) AS ref_recruiter,
                eeo_ethnic_type.type AS eeoEthnicType,
                eeo_veteran_type.type AS eeoVeteranType,
                candidate.eeo_disability_status AS eeoDisabilityStatus,
                candidate.eeo_gender AS eeoGender,
                IF (candidate.eeo_gender = 'm',
                    'Male',
                    IF (candidate.eeo_gender = 'f',
                        'Female',
                        ''))
                     AS eeoGenderText,                candidate.linkedin AS linkedin,                candidate.skypeid AS skypeid,                candidate.preflocations AS preflocations,                (candidate_visa.visa_type) AS visa,                candidate.title AS title,                candidate.totexp1 AS totexp1,                candidate.totexp2 AS totexp2,                candidate.relexp1 AS relexp1,                candidate.relexp2 AS relexp2,                (candidate_domain.domain_name) AS domain,                CONCAT(candidate_main_category.main_category,' - ',candidate_sub_category.sub_category) AS category,                (candidate_role.role_name) AS roles,                candidate.education AS education,                 candidate.college AS college,                candidate.graduation AS graduation,                (candidate_employment_type.employment_type) AS emptype,                candidate.authorization AS authorization,                candidate.courseone AS courseone,                candidate.coursetwo AS coursetwo,                candidate.coursethree AS coursethree,                 (supplier.name) AS suppliers,                candidate.resumeurl AS resumeurl
            FROM
                candidate
            LEFT JOIN user AS entered_by_user
                ON candidate.entered_by = entered_by_user.user_id
            LEFT JOIN user AS owner_user
                ON candidate.owner = owner_user.user_id            LEFT JOIN supplier                ON candidate.suppliers = supplier.supplier_id
            LEFT JOIN candidate_joborder
                ON candidate.candidate_id = candidate_joborder.candidate_id
            LEFT JOIN eeo_ethnic_type
                ON eeo_ethnic_type.eeo_ethnic_type_id = candidate.eeo_ethnic_type_id
            LEFT JOIN eeo_veteran_type
                ON eeo_veteran_type.eeo_veteran_type_id = candidate.eeo_veteran_type_id            LEFT JOIN user                ON candidate.ref_recruiter = user.user_id            LEFT JOIN candidate_visa                    ON candidate.visa = candidate_visa.visa_id            LEFT JOIN candidate_domain                ON  candidate.domain = candidate_domain.domain_id             LEFT JOIN candidate_sub_category                ON candidate.category = candidate_sub_category.sub_category_id             LEFT JOIN candidate_main_category                ON candidate_main_category.main_category_id = candidate_sub_category.main_category_id            LEFT JOIN candidate_role                                                  ON candidate.roles = candidate_role.role_id            LEFT JOIN candidate_employment_type                        ON  candidate.emptype = candidate_employment_type.employment_type_id
            WHERE
                candidate.candidate_id = %s
            AND
                candidate.site_id = %s
            GROUP BY
                candidate.candidate_id",
            $this->_db->makeQueryInteger($candidateID),
            PIPELINE_STATUS_SUBMITTED,
            $this->_siteID,
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );
        //echo $sql;        //exit;
        return $this->_db->getAssoc($sql);
    }

    /**
     * Returns all candidate information relevent for the Edit Candidate page
     * for a given candidate ID.
     *
     * @param integer Candidate ID.
     * @return array Associative result set array of candidate data, or array()
     *               if no records were returned.
     */
    public function getForEditing($candidateID)
    {
        $sql = sprintf(
            "SELECT
                candidate.candidate_id AS candidateID,
                candidate.is_active AS isActive,
                candidate.first_name AS firstName,
                candidate.middle_name AS middleName,
                candidate.last_name AS lastName,
                candidate.email1 AS email1,
                candidate.email2 AS email2,
                candidate.phone_home AS phoneHome,
                candidate.phone_work AS phoneWork,
                candidate.phone_cell AS phoneCell,
                candidate.address AS address,
                candidate.city AS city,
                candidate.country AS country,
                candidate.state AS state,
                candidate.zip AS zip,
                candidate.source AS source,
                candidate.key_skills AS keySkills,
                candidate.other_skills AS otherSkills,
                candidate.current_employer AS currentEmployer,
                candidate.current_pay AS currentPay,
                candidate.desired_pay AS desiredPay,
                candidate.notes AS notes,
                candidate.owner AS owner,
                candidate.can_relocate AS canRelocate,
                candidate.web_site AS webSite,
                candidate.best_time_to_call AS bestTimeToCall,
                candidate.is_hot AS isHot,
                candidate.is_prequalified AS isPrequalified,
                candidate.eeo_ethnic_type_id AS eeoEthnicTypeID,
                candidate.eeo_veteran_type_id AS eeoVeteranTypeID,
                candidate.eeo_disability_status AS eeoDisabilityStatus,
                candidate.eeo_gender AS eeoGender,
                candidate.is_admin_hidden AS isAdminHidden,                candidate.ref_recruiter AS ref_recruiter,                candidate.linkedin AS linkedin,                candidate.skypeid AS skypeid,                candidate.preflocations AS preflocations,                candidate.visa AS visa,                candidate.title AS title,                candidate.totexp1 AS totexp1,                candidate.totexp2 AS totexp2,                candidate.relexp1 AS relexp1,                candidate.relexp2 AS relexp2,                candidate.domain AS domain,                candidate.category AS category,                candidate.roles AS roles,                candidate.education AS education,                 candidate.college AS college,                candidate.graduation AS graduation,                candidate.emptype AS emptype,                 candidate.authorization AS authorization,                candidate.courseone AS courseone,                candidate.coursetwo AS coursetwo,                candidate.coursethree AS coursethree,                 candidate.suppliers AS suppliers,                candidate.resumeurl AS resumeurl,                DATE_FORMAT(
                    candidate.date_available, '%%m-%%d-%%y'
                ) AS dateAvailable
            FROM
                candidate
            WHERE
                candidate.candidate_id = %s
            AND
                candidate.site_id = %s",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );
        //echo $sql;        //exit;
        return $this->_db->getAssoc($sql);
    }

    // FIXME: Document me.
    public function getExport($IDs)
    {
        if (count($IDs) != 0)
        {
            $IDsValidated = array();
            
            foreach ($IDs as $id)
            {
                $IDsValidated[] = $this->_db->makeQueryInteger($id);
            }
            
            $criterion = 'AND candidate.candidate_id IN ('.implode(',', $IDsValidated).')';
        }
        else
        {
            $criterion = '';
        }

        $sql = sprintf(
            "SELECT
                candidate.candidate_id AS candidateID,
                candidate.last_name AS lastName,
                candidate.first_name AS firstName,
                candidate.phone_home AS phoneHome,
                candidate.phone_cell AS phoneCell,
                candidate.email1 AS email1,
                candidate.key_skills as keySkills
            FROM
                candidate
            WHERE
                candidate.site_id = %s
                %s
            ORDER BY
                candidate.last_name ASC,
                candidate.first_name ASC",
            $this->_siteID,
            $criterion
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Returns a candidate ID that matches the specified e-mail address.
     *
     * @param string Candidate e-mail address,
     * @return integer Candidate ID, or -1 if no matching candidates were
     *                 found.
     */
    public function getIDByEmail($email)
    {
        $sql = sprintf(
            "SELECT
                candidate.candidate_id AS candidateID
            FROM
                candidate
            WHERE
            (
                candidate.email1 = %s
                OR candidate.email2 = %s
            )
            AND
                candidate.site_id = %s",
            $this->_db->makeQueryString($email),
            $this->_db->makeQueryString($email),
            $this->_siteID
        );
        $rs = $this->_db->getAssoc($sql);

        if (empty($rs))
        {
            return -1;
        }

        return $rs['candidateID'];
    }
    public function getIDByPhone($phone)
    {
        $sql = sprintf(
            "SELECT
                candidate.candidate_id AS candidateID
            FROM
                candidate
            WHERE
            (
                candidate.phone_home = %s
                OR candidate.phone_cell = %s
                OR candidate.phone_work = %s
            )
            AND
                candidate.site_id = %s",
            $this->_db->makeQueryString($phone),
            $this->_db->makeQueryString($phone),
            $this->_db->makeQueryString($phone),
            $this->_siteID
        );
        $rs = $this->_db->getAssoc($sql);
         
        if (empty($rs))
        {
            return -1;
        }
         
        return $rs['candidateID'];
    }
     

    /**
     * Returns the number of candidates in the system.  Useful
     * for determining if the friendly "no candidates in system"
     * should be displayed rather than the datagrid.
     *
     * @param boolean Include administratively hidden candidates?
     * @return integer Number of Candidates in site.
     */
    public function getCount($allowAdministrativeHidden = false)
    {
        if (!$allowAdministrativeHidden)
        {
            $adminHiddenCriterion = 'AND candidate.is_admin_hidden = 0';
        }
        else
        {
            $adminHiddenCriterion = '';
        }

        $sql = sprintf(
            "SELECT
                COUNT(*) AS totalCandidates
            FROM
                candidate
            WHERE
                candidate.site_id = %s
            %s",
            $this->_siteID,
            $adminHiddenCriterion
        );

        return $this->_db->getColumn($sql, 0, 0);
    }

    /**
     * Returns the entire candidates list.
     *
     * @param boolean Include administratively hidden candidates?
     * @return array Multi-dimensional associative result set array of
     *               candidates data, or array() if no records were returned.
     */
    public function getAll($allowAdministrativeHidden = false)
    {
        if (!$allowAdministrativeHidden)
        {
            $adminHiddenCriterion = 'AND candidate.is_admin_hidden = 0';
        }
        else
        {
            $adminHiddenCriterion = '';
        }

        $sql = sprintf(
            "SELECT
                candidate.candidate_id AS candidateID,
                candidate.last_name AS lastName,
                candidate.first_name AS firstName,
                candidate.phone_home AS phoneHome,
                candidate.phone_cell AS phoneCell,
                candidate.email1 AS email1,
                candidate.key_skills AS keySkills,
                candidate.is_hot AS isHot,
                candidate.is_prequalified AS isPrequalified,                candidate.ref_recruiter AS ref_recruiter,
                DATE_FORMAT(
                    candidate.date_created, '%%m-%%d-%%y'
                ) AS dateCreated,
                DATE_FORMAT(
                    candidate.date_modified, '%%m-%%d-%%y'
                ) AS dateModified,
                candidate.date_created AS dateCreatedSort,
                owner_user.first_name AS ownerFirstName,
                owner_user.last_name AS ownerLastName
            FROM
                candidate
            LEFT JOIN user AS owner_user
                ON candidate.entered_by = user.user_id
            WHERE
                candidate.site_id = %s
            %s
            ORDER BY
                candidate.last_name ASC,
                candidate.first_name ASC",
            $this->_siteID,
            $adminHiddenCriterion
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Returns all resumes for a candidate.
     *
     * @param integer Candidate ID.
     * @return array Multi-dimensional associative result set array of
     *               candidate attachments data, or array() if no records were
     *               returned.
     */
    public function getResumes($candidateID)
    {
        $sql = sprintf(
            "SELECT
                attachment.attachment_id AS attachmentID,
                attachment.data_item_id AS candidateID,
                attachment.title AS title,
                attachment.text AS text
            FROM
                attachment
            WHERE
                resume = 1
            AND
                attachment.data_item_type = %s
            AND
                attachment.data_item_id = %s
            AND
                attachment.site_id = %s",
            DATA_ITEM_CANDIDATE,
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Returns a candidate resume attachment by attachment.
     *
     * @param integer Attachment ID.
     * @return array Associative result set array of candidate / attachment
     *               data, or array() if no records were returned.
     */
    public function getResume($attachmentID)
    {
        $sql = sprintf(
            "SELECT
                attachment.attachment_id AS attachmentID,
                attachment.data_item_id AS candidateID,
                attachment.title AS title,
                attachment.text AS text,
                candidate.first_name AS firstName,
                candidate.last_name AS lastName
            FROM
                attachment
            LEFT JOIN candidate
                ON attachment.data_item_id = candidate.candidate_id
                AND attachment.site_id = candidate.site_id
            WHERE
                attachment.resume = 1
            AND
                attachment.attachment_id = %s
            AND
                attachment.site_id = %s",
            $this->_db->makeQueryInteger($attachmentID),
            $this->_siteID
        );

        return $this->_db->getAssoc($sql);
    }

    /**
     * Returns an array of job orders data (jobOrderID, title, companyName)
     * for the specified candidate ID.
     *
     * @param integer Candidate ID,
     * @return array Multi-dimensional associative result set array of
     *               job orders data, or array() if no records were returned.
     */
    public function getJobOrdersArray($candidateID)
    {
        $sql = sprintf(
            "SELECT
                joborder.joborder_id AS jobOrderID,
                joborder.title AS title,
                company.name AS companyName
            FROM
                joborder
            LEFT JOIN company
                ON joborder.company_id = company.company_id
            LEFT JOIN candidate_joborder
                ON joborder.joborder_id = candidate_joborder.joborder_id
            WHERE
                candidate_joborder.candidate_id = %s
            AND
                joborder.site_id = %s
            ORDER BY
                title ASC",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
     }

    /**
     * Updates a candidate's modified timestamp.
     *
     * @param integer Candidate ID.
     * @return boolean Boolean was the query executed successfully?
     */
    public function updateModified($candidateID)
    {
        $sql = sprintf(
            "UPDATE
                candidate
            SET
                date_modified = NOW()
            WHERE
                candidate_id = %s
            AND
                site_id = %s",
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );

        return (boolean) $this->_db->query($sql);
    }

    /**
     * Returns all upcoming events for the candidate.
     *
     * @param integer Candidate ID.
     * @return array Multi-dimensional associative result set array of
     *               candidate events data, or array() if no records were
     *               returned.
     */
    public function getUpcomingEvents($candidateID)
    {
        $calendar = new Calendar($this->_siteID);
        return $calendar->getUpcomingEventsByDataItem(
            DATA_ITEM_CANDIDATE, $candidateID
        );
    }

    /**
     * Gets all possible source suggestions for a site.
     *
     * @return array Multi-dimensional associative result set array of
     *               candidate sources data.
     */
    public function getPossibleSources()
    {
        $sql = sprintf(
            "SELECT
                candidate_source.source_id AS sourceID,
                candidate_source.name AS name
            FROM
                candidate_source
            WHERE
                candidate_source.site_id = %s
            ORDER BY
                candidate_source.name ASC",
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
    }        
    public function getVisaList()    {        $sql = sprintf(                        "SELECT                            candidate_visa.visa_id AS visa_id,                            candidate_visa.visa_type AS visa_type                        FROM                            candidate_visa                        WHERE                            candidate_visa.site_id = %s                        ORDER BY                            candidate_visa.visa_type ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }            public function getDomainList()    {        $sql = sprintf(                        "SELECT                            candidate_domain.domain_id AS domain_id,                            candidate_domain.domain_name AS domain_name                        FROM                            candidate_domain                        WHERE                            candidate_domain.site_id = %s                        ORDER BY                            candidate_domain.domain_name ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }            public function getMainCategory()    {        $sql = sprintf(                        "SELECT                            candidate_main_category.main_category_id AS main_category_id,                            candidate_main_category.main_category AS main_category                        FROM                            candidate_main_category            WHERE                            candidate_main_category.site_id = %s                        ORDER BY                            candidate_main_category.main_category ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }            public function getSubCategory()    {        $sql = sprintf(                        "SELECT                            candidate_sub_category.sub_category_id AS sub_category_id,                candidate_sub_category.main_category_id AS main_category_id,                            candidate_sub_category.sub_category AS sub_category                        FROM                            candidate_sub_category            WHERE                            candidate_sub_category.site_id = %s                        ORDER BY                            candidate_sub_category.sub_category ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }            public function getCategory()    {        $sql = sprintf(                        "SELECT                            candidate_sub_category.sub_category_id AS category_id,                            CONCAT(candidate_main_category.main_category,' - ',candidate_sub_category.sub_category) AS category                        FROM                            candidate_sub_category,candidate_main_category                        WHERE                            candidate_sub_category.main_category_id = candidate_main_category.main_category_id                                 AND                candidate_sub_category.site_id = %s                        ORDER BY                            candidate_main_category.main_category ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }        public function getCandidateRoles()    {        $sql = sprintf(                        "SELECT                            candidate_role.role_id AS role_id,                            candidate_role.role_name AS role_name                        FROM                            candidate_role                        WHERE                            candidate_role.site_id = %s                        ORDER BY                            candidate_role.role_name ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }         public function getEmploymentType()    {        $sql = sprintf(                        "SELECT                            candidate_employment_type.employment_type_id AS employment_type_id,                            candidate_employment_type.employment_type AS employment_type                                    FROM                            candidate_employment_type                        WHERE                            candidate_employment_type.site_id = %s                        ORDER BY                            candidate_employment_type.employment_type ASC",                        $this->_siteID                        );                return $this->_db->getAllAssoc($sql);    }    
    /**
     * Updates a sites possible sources with an array generated
     * by getDifferencesFromList (ListEditor.php).
     *
     * @param array Result of ListEditor::getDifferencesFromList().
     * @return void
     */
    public function updatePossibleSources($updates)
    {
        $history = new History($this->_siteID);

        foreach ($updates as $update)
        {
            switch ($update[2])
            {
                case LIST_EDITOR_ADD:
                    $sql = sprintf(
                        "INSERT INTO candidate_source (
                            name,
                            site_id,
                            date_created
                         )
                         VALUES (
                            %s,
                            %s,
                            NOW()
                         )",
                         $this->_db->makeQueryString($update[0]),
                         $this->_siteID
                    );
                    $this->_db->query($sql);

                    break;

                case LIST_EDITOR_REMOVE:
                    $sql = sprintf(
                        "DELETE FROM
                            candidate_source
                         WHERE
                            source_id = %s
                         AND
                            site_id = %s",
                         $update[1],
                         $this->_siteID
                    );
                    $this->_db->query($sql);

                    break;

                case LIST_EDITOR_MODIFY:
                    $sql = sprintf(
                        "SELECT
                            name
                         FROM
                            candidate_source
                         WHERE
                            source_id = %s
                         AND
                            site_id = %s",
                         $this->_db->makeQueryInteger($update[1]),
                         $this->_siteID
                    );
                    $firstSource = $this->_db->getAssoc($sql);

                    $sql = sprintf(
                        "UPDATE
                            candidate
                         SET
                            source = %s
                         WHERE
                            source = %s
                         AND
                            site_id = %s",
                         $update[1],
                         $this->_db->makeQueryString($firstSource['name']),
                         $this->_siteID
                    );
                    $this->_db->query($sql);

                    $sql = sprintf(
                        "UPDATE
                            candidate_source
                         SET
                            name = %s
                         WHERE
                            source_id = %s
                         AND
                            site_id = %s",
                         $this->_db->makeQueryString($update[0]),
                         $this->_db->makeQueryInteger($update[1]),
                         $this->_siteID
                    );
                    $this->_db->query($sql);

                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Changes the administrative hide / show flag.
     * Only can be accessed by a MSA or higher user.
     *
     * @param integer Candidate ID.
     * @param boolean Administratively hide this candidate?
     * @return boolean Was the query executed successfully?
     */    
    public function administrativeHideShow($candidateID, $state)
    {
        $sql = sprintf(
            "UPDATE
                candidate
            SET
                is_admin_hidden = %s
            WHERE
                candidate_id = %s
            AND
                site_id = %s",
            ($state ? 1 : 0),
            $this->_db->makeQueryInteger($candidateID),
            $this->_siteID
        );

        return (boolean) $this->_db->query($sql);
    }
}


class CandidatesDataGrid extends DataGrid
{
    protected $_siteID;

    // FIXME: Fix ugly indenting - ~400 character lines = bad.
    public function __construct($instanceName, $siteID, $parameters, $misc = 0)
    {
        $this->_db = DatabaseConnection::getInstance();
        $this->_siteID = $siteID;
        $this->_assignedCriterion = "";
        $this->_dataItemIDColumn = 'candidate.candidate_id';

        $this->_classColumns = array(
            'Attachments' => array('select' => 'IF(candidate_joborder_submitted.candidate_joborder_id, 1, 0) AS submitted,
                                                IF(attachment_id, 1, 0) AS attachmentPresent',

                                     'pagerRender' => 'if ($rsData[\'submitted\'] == 1)
                                                    {
                                                        $return = \'<img src="images/job_orders.gif" alt="" width="16" height="16" title="Submitted for a Job Order" />\';
                                                    }
                                                    else
                                                    {
                                                        $return = \'<img src="images/mru/blank.gif" alt="" width="16" height="16" />\';
                                                    }

                                                    if ($rsData[\'attachmentPresent\'] == 1)
                                                    {
                                                        $return .= \'<img src="images/paperclip.gif" alt="" width="16" height="16" title="Attachment Present" />\';
                                                    }
                                                    else
                                                    {
                                                        $return .= \'<img src="images/mru/blank.gif" alt="" width="16" height="16" />\';
                                                    }

                                                    return $return;
                                                   ',

                                     'join'     => 'LEFT JOIN attachment
                                                        ON candidate.candidate_id = attachment.data_item_id
														AND attachment.data_item_type = '.DATA_ITEM_CANDIDATE.'
                                                    LEFT JOIN candidate_joborder AS candidate_joborder_submitted
                                                        ON candidate_joborder_submitted.candidate_id = candidate.candidate_id
                                                        AND candidate_joborder_submitted.status >= '.PIPELINE_STATUS_SUBMITTED.'
                                                        AND candidate_joborder_submitted.site_id = '.$this->_siteID.'
                                                        AND candidate_joborder_submitted.status != '.PIPELINE_STATUS_NOTINCONSIDERATION,
                                     'pagerWidth'    => 34,
                                     'pagerOptional' => true,
                                     'pagerNoTitle' => true,
                                     'sizable'  => false,
                                     'exportable' => false,
                                     'filterable' => false),

            'First Name' =>     array('select'         => 'candidate.first_name AS firstName',
                                      'pagerRender'    => 'if ($rsData[\'isHot\'] == 1) $className =  \'jobLinkHot\'; else $className = \'jobLinkCold\'; return \'<a href="'.CATSUtility::getIndexName().'?m=candidates&amp;a=show&amp;candidateID=\'.$rsData[\'candidateID\'].\'" class="\'.$className.\'">\'.htmlspecialchars($rsData[\'firstName\']).\'</a>\';',
                                      'sortableColumn' => 'firstName',
                                      'pagerWidth'     => 75,
                                      'pagerOptional'  => false,
                                      'alphaNavigation'=> true,
                                      'filter'         => 'candidate.first_name'),

            'Last Name' =>      array('select'         => 'candidate.last_name AS lastName',
                                     'sortableColumn'  => 'lastName',
                                     'pagerRender'     => 'if ($rsData[\'isHot\'] == 1) $className =  \'jobLinkHot\'; else $className = \'jobLinkCold\'; return \'<a href="'.CATSUtility::getIndexName().'?m=candidates&amp;a=show&amp;candidateID=\'.$rsData[\'candidateID\'].\'" class="\'.$className.\'">\'.htmlspecialchars($rsData[\'lastName\']).\'</a>\';',
                                     'pagerWidth'      => 85,
                                     'pagerOptional'   => false,
                                     'alphaNavigation' => true,
                                     'filter'         => 'candidate.last_name'),
/*
            'E-Mail' =>         array('select'   => 'candidate.email1 AS email1',
                                     'sortableColumn'     => 'email1',
                                     'pagerWidth'    => 80,
                                     'filter'         => 'candidate.email1'),

            '2nd E-Mail' =>     array('select'   => 'candidate.email2 AS email2',
                                     'sortableColumn'     => 'email2',
                                     'pagerWidth'    => 80,
                                     'filter'         => 'candidate.email2'),

            'Home Phone' =>     array('select'   => 'candidate.phone_home AS phoneHome',
                                     'sortableColumn'     => 'phoneHome',
                                     'pagerWidth'    => 80,
                                     'filter'         => 'candidate.phone_home'),

            'Cell Phone' =>     array('select'   => 'candidate.phone_cell AS phoneCell',
                                     'sortableColumn'     => 'phoneCell',
                                     'pagerWidth'    => 80,
                                     'filter'         => 'candidate.phone_cell'),

            'Work Phone' =>     array('select'   => 'candidate.phone_work AS phoneWork',
                                     'sortableColumn'     => 'phoneWork',
                                     'pagerWidth'    => 80,
                                     'filter'         => 'candidate.phone_work'),*/

            'Address' =>        array('select'   => 'candidate.address AS address',
                                     'sortableColumn'     => 'address',
                                     'pagerWidth'    => 250,
                                     'alphaNavigation' => true,
                                     'filter'         => 'candidate.address'),

            'City' =>           array('select'   => 'candidate.city AS city',
                                     'sortableColumn'     => 'city',
                                     'pagerWidth'    => 80,
                                     'alphaNavigation' => true,
                                     'filter'         => 'candidate.city'),


            'State' =>          array('select'   => 'candidate.state AS state',
                                     'sortableColumn'     => 'state',
                                     'filterType' => 'dropDown',
                                     'pagerWidth'    => 50,
                                     'alphaNavigation' => true,
                                     'filter'         => 'candidate.state'),

            'Zip' =>            array('select'  => 'candidate.zip AS zip',
                                     'sortableColumn'    => 'zip',
                                     'pagerWidth'   => 50,
                                     'filter'         => 'candidate.zip'),

            'Misc Notes' =>     array('select'  => 'candidate.notes AS notes',
                                     'sortableColumn'    => 'notes',
                                     'pagerWidth'   => 300,
                                     'filter'         => 'candidate.notes'),

            'Web Site' =>      array('select'  => 'candidate.web_site AS webSite',
                                     'pagerRender'     => 'return \'<a href="\'.htmlspecialchars($rsData[\'webSite\']).\'">\'.htmlspecialchars($rsData[\'webSite\']).\'</a>\';',
                                     'sortableColumn'    => 'webSite',
                                     'pagerWidth'   => 80,
                                     'filter'         => 'candidate.web_site'),
            'Recent Status' => array('select'  => '(
                                                    SELECT
                                                        CONCAT(
                                                            \'<a href="'.CATSUtility::getIndexName().'?m=joborders&amp;a=show&amp;jobOrderID=\',
                                                            joborder.joborder_id,
                                                            \'" title="\',
                                                            joborder.title,
                                                            \' (\',
                                                            company.name,
                                                            \')">\',
                                                            candidate_joborder_status.short_description,
                                                            \'</a>\'
                                                        )
                                                    FROM
                                                        candidate_joborder
                                                    LEFT JOIN candidate_joborder_status
                                                        ON candidate_joborder_status.candidate_joborder_status_id = candidate_joborder.status
                                                    LEFT JOIN joborder
                                                        ON joborder.joborder_id = candidate_joborder.joborder_id
                                                    LEFT JOIN company
                                                        ON joborder.company_id = company.company_id
                                                    WHERE
                                                        candidate_joborder.candidate_id = candidate.candidate_id
                                                    ORDER BY
                                                        candidate_joborder.date_modified DESC
                                                    LIMIT 1
                                                ) AS lastStatus
                                                ',
                                     'sort'    => 'lastStatus',
                                     'pagerRender'     => 'return $rsData[\'lastStatus\'];',
                                     'exportRender'     => 'return $rsData[\'lastStatus\'];',
                                     'pagerWidth'   => 140,
                                     'exportable' => false,
                                     'filterHaving'  => 'lastStatus',
                                     'filterTypes'   => '=~'),

            'Recent Status (Extended)' => array('select'  => '(
                                                    SELECT
                                                        CONCAT(
                                                            candidate_joborder_status.short_description,
                                                            \'<br />\',
                                                            \'<a href="'.CATSUtility::getIndexName().'?m=companies&amp;a=show&amp;companyID=\',
                                                            company.company_id,
                                                            \'">\',
                                                            company.name,
                                                            \'</a> - \',
                                                            \'<a href="'.CATSUtility::getIndexName().'?m=joborders&amp;a=show&amp;jobOrderID=\',
                                                            joborder.joborder_id,
                                                            \'">\',
                                                            joborder.title,
                                                            \'</a>\'
                                                        )
                                                    FROM
                                                        candidate_joborder
                                                    LEFT JOIN candidate_joborder_status
                                                        ON candidate_joborder_status.candidate_joborder_status_id = candidate_joborder.status
                                                    LEFT JOIN joborder
                                                        ON joborder.joborder_id = candidate_joborder.joborder_id
                                                    LEFT JOIN company
                                                        ON joborder.company_id = company.company_id
                                                    WHERE
                                                        candidate_joborder.candidate_id = candidate.candidate_id
                                                    ORDER BY
                                                        candidate_joborder.date_modified DESC
                                                    LIMIT 1
                                                ) AS lastStatusLong
                                                ',
                                     'sortableColumn'    => 'lastStatusLong',
                                     'pagerRender'     => 'return $rsData[\'lastStatusLong\'];',
                                     'pagerWidth'   => 310,
                                     'exportable' => false,
                                     'filterable' => false),                          

            'Key Skills' =>    array('select'  => 'candidate.key_skills AS keySkills',
                                     'pagerRender' => 'return substr(trim($rsData[\'keySkills\']), 0, 30) . (strlen(trim($rsData[\'keySkills\'])) > 30 ? \'...\' : \'\');',
                                     'sortableColumn'    => 'keySkills',
                                     'pagerWidth'   => 210,
                                     'filter'         => 'candidate.key_skills'),

            

            'Source' =>        array('select'  => 'candidate.source AS source',
                                     'sortableColumn'    => 'source',
                                     'pagerWidth'   => 140,
                                     'alphaNavigation' => true,
                                     'filter'         => 'candidate.source'),

            'Available' =>     array('select'   => 'DATE_FORMAT(candidate.date_available, \'%m-%d-%y\') AS dateAvailable',
                                     'sortableColumn'     => 'dateAvailable',
                                     'pagerWidth'    => 60),

            'Current Employer' => array('select'  => 'candidate.current_employer AS currentEmployer',
                                     'sortableColumn'    => 'currentEmployer',
                                     'pagerWidth'   => 125,
                                     'alphaNavigation' => true,
                                     'filter'         => 'candidate.current_employer'),

            'Current Pay' => array('select'  => 'candidate.current_pay AS currentPay',
                                     'sortableColumn'    => 'currentPay',
                                     'pagerWidth'   => 125,
                                     'filter'         => 'candidate.current_pay',
                                     'filterTypes'   => '===>=<'),

            'Desired Pay' => array('select'  => 'candidate.desired_pay AS desiredPay',
                                     'sortableColumn'    => 'desiredPay',
                                     'pagerWidth'   => 125,
                                     'filter'         => 'candidate.desired_pay',
                                     'filterTypes'   => '===>=<'),

            'Can Relocate'  => array('select'  => 'candidate.can_relocate AS canRelocate',
                                     'pagerRender'     => 'return ($rsData[\'canRelocate\'] == 0 ? \'No\' : \'Yes\');',
                                     'exportRender'     => 'return ($rsData[\'canRelocate\'] == 0 ? \'No\' : \'Yes\');',
                                     'sortableColumn'    => 'canRelocate',
                                     'pagerWidth'   => 80,
                                     'filter'         => 'candidate.can_relocate'),

            'Owner' =>         array('select'   => 'owner_user.first_name AS ownerFirstName,' .
                                                   'owner_user.last_name AS ownerLastName,' .
                                                   'CONCAT(owner_user.last_name, owner_user.first_name) AS ownerSort',
                                     'join'     => 'LEFT JOIN user AS owner_user ON candidate.owner = owner_user.user_id',
                                     'pagerRender'      => 'return StringUtility::makeInitialName($rsData[\'ownerFirstName\'], $rsData[\'ownerLastName\'], false, LAST_NAME_MAXLEN);',
                                     'exportRender'     => 'return $rsData[\'ownerFirstName\'] . " " .$rsData[\'ownerLastName\'];',
                                     'sortableColumn'     => 'ownerSort',
                                     'pagerWidth'    => 75,
                                     'alphaNavigation' => true,
                                     'filter'         => 'CONCAT(owner_user.first_name, owner_user.last_name)'),

            'Created' =>       array('select'   => 'DATE_FORMAT(candidate.date_created, \'%m-%d-%y\') AS dateCreated',
                                     'pagerRender'      => 'return $rsData[\'dateCreated\'];',
                                     'sortableColumn'     => 'dateCreatedSort',
                                     'pagerWidth'    => 60,
                                     'filterHaving' => 'DATE_FORMAT(candidate.date_created, \'%m-%d-%y\')'),

            'Modified' =>      array('select'   => 'DATE_FORMAT(candidate.date_modified, \'%m-%d-%y\') AS dateModified',
                                     'pagerRender'      => 'return $rsData[\'dateModified\'];',
                                     'sortableColumn'     => 'dateModifiedSort',
                                     'pagerWidth'    => 60,
                                     'pagerOptional' => false,
                                     'filterHaving' => 'DATE_FORMAT(candidate.date_modified, \'%m-%d-%y\')'),

            /* This one only works when called from the saved list view.  Thats why it is not optional, filterable, or exportable.
             * FIXME:  Somehow make this defined in the associated savedListDataGrid class child.
             */
            'Added To List' =>  array('select'   => 'DATE_FORMAT(saved_list_entry.date_created, \'%m-%d-%y\') AS dateAddedToList,
                                                     saved_list_entry.date_created AS dateAddedToListSort',
                                     'pagerRender'      => 'return $rsData[\'dateAddedToList\'];',
                                     'sortableColumn'     => 'dateAddedToListSort',
                                     'pagerWidth'    => 60,
                                     'pagerOptional' => false,
                                     'filterable' => false,
                                     'exportable' => false),

            'OwnerID' =>       array('select'    => '',
                                     'filter'    => 'candidate.owner',
                                     'pagerOptional' => false,
                                     'filterable' => false,
                                     'filterDescription' => 'Only My Candidates'),

            'IsHot' =>         array('select'    => '',
                                     'filter'    => 'candidate.is_hot',
                                     'pagerOptional' => false,
                                     'filterable' => false,
                                     'filterDescription' => 'Only Hot Candidates'),
            'IsPrequalified' =>         array('select'    => '',
                                     'filter'    => 'candidate.is_prequalified',
                                     'pagerOptional' => false,
                                     'filterable' => false,
                                     'filterDescription' => 'Only Pre-Qualified'),                          
        // Tags filtering
        	'Tags'	=>			array(
                                     'select'	=> '(
                                                    SELECT TRIM(GROUP_CONCAT(\' \',t2.title))	FROM candidate_tag t1
                                                    LEFT JOIN tag t2 ON t1.tag_id = t2.tag_id
                                                    WHERE t1.candidate_id = candidate.candidate_id
                                                    GROUP BY candidate_id
                                                    ) as tags
                                                    ',
                                     'sortableColumn' => 'tags',
                                     'pagerRender'    => 'return $rsData[\'tags\'];',
                                     'pagerOptional' => false,
                                     'pagerWidth'     => 310,
                                     'exportable'     => false,
                                     'filterable'     => false,

                                     'filterTypes'    => '=#',
                                     'filterRender=#' => '
                                      return "candidate.candidate_id IN (
                                         SELECT t1.candidate_id tags FROM candidate t1
                                         LEFT JOIN candidate_tag t2 ON t1.candidate_id = t2.candidate_id
                                         WHERE t2.site_id = 1 AND t2.tag_id IN (". implode(",",$arguments)."))";
                                     ')
        );

        if (US_ZIPS_ENABLED)
        {
            $this->_classColumns['Near Zipcode'] =
                               array('select'  => 'candidate.zip AS zip',
                                     'filter' => 'candidate.zip',
                                     'pagerOptional' => false,
                                     'filterTypes'   => '=@');
        }

        /* Extra fields get added as columns here. */
        $candidates = new Candidates($this->_siteID);
        $extraFieldsRS = $candidates->extraFields->getSettings();
        foreach ($extraFieldsRS as $index => $data)
        {
            $fieldName = $data['fieldName'];

            if (!isset($this->_classColumns[$fieldName]))
            {
                $columnDefinition = $candidates->extraFields->getDataGridDefinition($index, $data, $this->_db);

                /* Return false for extra fields that should not be columns. */
                if ($columnDefinition !== false)
                {
                    $this->_classColumns[$fieldName] = $columnDefinition;
                }
            }
        }

        parent::__construct($instanceName, $parameters, $misc);
    }

    /**
     * Returns the sql statment for the pager.
     *
     * @return array Candidates data
     */
    public function getSQL($selectSQL, $joinSQL, $whereSQL, $havingSQL, $orderSQL, $limitSQL, $distinct = '')
    {
        // FIXME: Factor out Session dependency.
        if ($_SESSION['CATS']->isLoggedIn() && $_SESSION['CATS']->getAccessLevel() < ACCESS_LEVEL_MULTI_SA)
        {
            $adminHiddenCriterion = 'AND candidate.is_admin_hidden = 0';
        }
        else
        {
            $adminHiddenCriterion = '';
        }

        if ($this->getMiscArgument() != 0)
        {
            $savedListID = (int) $this->getMiscArgument();
            $joinSQL  .= ' INNER JOIN saved_list_entry
                                    ON saved_list_entry.data_item_type = '.DATA_ITEM_CANDIDATE.'
                                    AND saved_list_entry.data_item_id = candidate.candidate_id
                                    AND saved_list_entry.site_id = '.$this->_siteID.'
                                    AND saved_list_entry.saved_list_id = '.$savedListID;
        }
        else
        {
            $joinSQL  .= ' LEFT JOIN saved_list_entry
                                    ON saved_list_entry.data_item_type = '.DATA_ITEM_CANDIDATE.'
                                    AND saved_list_entry.data_item_id = candidate.candidate_id
                                    AND saved_list_entry.site_id = '.$this->_siteID;         
        }

        $sql = sprintf(
            "SELECT SQL_CALC_FOUND_ROWS %s
                candidate.candidate_id AS candidateID,
                candidate.candidate_id AS exportID,
                candidate.is_hot AS isHot,
                candidate.is_prequalified AS isPrequalified,
                candidate.date_modified AS dateModifiedSort,
                candidate.date_created AS dateCreatedSort,
            %s
            FROM
                candidate
            %s
            WHERE
                candidate.site_id = %s
            %s
            %s
            %s
            GROUP BY candidate.candidate_id
            %s
            %s
            %s",
            $distinct,
            $selectSQL,
            $joinSQL,
            $this->_siteID,
            $adminHiddenCriterion,
            (strlen($whereSQL) > 0) ? ' AND ' . $whereSQL : '',
            $this->_assignedCriterion,
            (strlen($havingSQL) > 0) ? ' HAVING ' . $havingSQL : '',
            $orderSQL,
            $limitSQL
        );

        return $sql;
    }
}

/**
 *  EEO Settings Library
 *  @package    CATS
 *  @subpackage Library
 */
class EEOSettings
{
    private $_db;
    private $_siteID;
    private $_userID;


    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        // FIXME: Factor out Session dependency.
        $this->_userID = $_SESSION['CATS']->getUserID();
        $this->_db = DatabaseConnection::getInstance();
    }


    /**
     * Returns all EEO settings for a site.
     *
     * @return array (setting => value)
     */
    public function getAll()
    {
        /* Default values. */
        $settings = array(
            'enabled' => '0',
            'genderTracking' => '0',
            'ethnicTracking' => '0',
            'veteranTracking' => '0',
            'veteranTracking' => '0',
            'disabilityTracking' => '0',
            'canSeeEEOInfo' => false
        );

        $sql = sprintf(
            "SELECT
                settings.setting AS setting,
                settings.value AS value,
                settings.site_id AS siteID
            FROM
                settings
            WHERE
                settings.site_id = %s
            AND
                settings.settings_type = %s",
            $this->_siteID,
            SETTINGS_EEO
        );
        $rs = $this->_db->getAllAssoc($sql);

        /* Override default settings with settings from the database. */
        foreach ($rs as $rowIndex => $row)
        {
            foreach ($settings as $setting => $value)
            {
                if ($row['setting'] == $setting)
                {
                    $settings[$setting] = $row['value'];
                }
            }
        }

        $settings['canSeeEEOInfo'] = $_SESSION['CATS']->canSeeEEOInfo();

        return $settings;
    }

    /**
     * Sets an EEO setting for a site.
     *
     * @param string Setting name
     * @param string Setting value
     * @return void
     */
    public function set($setting, $value)
    {
        $sql = sprintf(
            "DELETE FROM
                settings
            WHERE
                settings.setting = %s
            AND
                site_id = %s
            AND
                settings_type = %s",
            $this->_db->makeQueryStringOrNULL($setting),
            $this->_siteID,
            SETTINGS_EEO
        );
        $this->_db->query($sql);

        $sql = sprintf(
            "INSERT INTO settings (
                setting,
                value,
                site_id,
                settings_type
            )
            VALUES (
                %s,
                %s,
                %s,
                %s
            )",
            $this->_db->makeQueryStringOrNULL($setting),
            $this->_db->makeQueryStringOrNULL($value),
            $this->_siteID,
            SETTINGS_EEO
         );
         $this->_db->query($sql);
    }
}

?>

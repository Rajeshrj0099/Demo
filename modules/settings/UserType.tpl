<?php TemplateUtility::printHeader('Settings', array('modules/settings/validator.js', 'js/sorttable.js')); ?>
<div id="main">
        <div id="contents">
            <table width= "100%">
                <tr>
                    <td width="3%" valign="bottom">
                        <img src="images/settings.gif" width="24" height="24" border="0" alt="Settings" style="margin-top: 3px;" />&nbsp;
                    </td>
                    <td valign="bottom"><h2>Settings: Add User Type</h2></td>
                </tr>
            </table>

            <p class="note">Add User Type</p>

            <form name="addUserForm" id="addUserForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=addUserType" method="post" onsubmit="return checkAddUserForm(document.addUserForm);" autocomplete="off">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <table width="100%">
                    <tr>
                        <td align="left" valign="top">
                            <table class="editTable" width="100%">
                                <tr>
                                    <td class="tdVertical">
                                        <label id="firstNameLabel" for="firstName">User Type :</label>
                                    </td>
                                    <td class="tdData">
                                </tr>

                                <tr>
                                    <td class="tdVertical">
                                        <label id="moduleLabel" for="module">Modules:</label>
                                    </td>
                                    <td class="tdData">
										<?php foreach ($this->ModuleList as $rowNumber => $ModuleListData): ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <input type="submit" class="button" id="submit"   value="Save" onclick="parentGoToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=manageUserType');"/>
            </form>
        </div>
    </div>
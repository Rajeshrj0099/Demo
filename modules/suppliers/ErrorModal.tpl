<?php /* $Id: ErrorModal.tpl 1889 2007-02-20 05:21:54Z will $ */ ?>
<?php TemplateUtility::printModalHeader('Suppliers'); ?>
    <table>
        <tr>
            <td width="3%">
                <img src="images/suppliers.gif" width="24" height="24" border="0" alt="Suppliers" style="margin-top: 3px;" />&nbsp;
            </td>
            <td><h2>Suppliers: Error</h2></td>
        </tr>
    </table>

    <p class="fatalError">
        A fatal error has occurred.<br />
        <br />
        <?php echo($this->errorMessage); ?>
    </p>
    </body>
</html>


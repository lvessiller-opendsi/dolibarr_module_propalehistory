<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2013 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup	mymodule	MyModule module
 * 	\brief		MyModule module descriptor.
 * 	\file		core/modules/modMyModule.class.php
 * 	\ingroup	mymodule
 * 	\brief		Description and activation file for module MyModule
 */
include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

/**
 * Description and activation class for module MyModule
 */
class modPropalehistory extends DolibarrModules
{

    /**
     * 	Constructor. Define names, constants, directories, boxes, permissions
     *
     * 	@param	DoliDB		$db	Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;

		$this->editor_name = 'ATM-Consulting';
		$this->editor_url = "https://www.atm-consulting.fr";
        // Id for module (must be unique).
        // Use a free id here
        // (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 104090; // 104000 to 104999 for ATM CONSULTING
        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'propalhistory';

        // Family can be 'crm','financial','hr','projects','products','ecm','technic','other'
        // It is used to group modules in module setup page
        $this->family = 'ATM Consulting - CRM';
        // Module label (no space allowed)
        // used if translation string 'ModuleXXXName' not found
        // (where XXX is value of numeric property 'numero' of module)
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description
        // used if translation string 'ModuleXXXDesc' not found
        // (where XXX is value of numeric property 'numero' of module)
        $this->description = "Gestion de l'historique des propositions commerciales";
        // Possible values for version are: 'development', 'experimental' or version
        $this->version = '2.0.1';
        // Key used in llx_const table to save module status enabled/disabled
        // (where MYMODULE is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        // Where to store the module in setup page
        // (0=common,1=interface,2=others,3=very specific)
        $this->special = 0;
        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png
        // use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png
        // use this->picto='pictovalue@module'
        $this->picto = 'propalehistory@propalehistory'; // mypicto@mymodule
        // Defined all module parts (triggers, login, substitutions, menus, css, etc...)
        // for default path (eg: /mymodule/core/xxxxx) (0=disable, 1=enable)
        // for specific path of parts (eg: /mymodule/core/modules/barcode)
        // for specific css file (eg: /mymodule/css/mymodule.css.php)
        $this->module_parts = array(
            // Set this to 1 if module has its own trigger directory
            'triggers' => 1,
            // Set this to 1 if module has its own login method directory
            //'login' => 0,
            // Set this to 1 if module has its own substitution function file
            //'substitutions' => 0,
            // Set this to 1 if module has its own menus handler directory
            //'menus' => 0,
            // Set this to 1 if module has its own barcode directory
            //'barcode' => 0,
            // Set this to 1 if module has its own models directory
            //'models' => 0,
            // Set this to relative path of css if module has its own css file
            //'css' => '/mymodule/css/mycss.css.php',
            // Set here all hooks context managed by module
            'hooks' => array('propalcard','pdfgeneration')
            // Set here all workflow context managed by module
            //'workflow' => array('order' => array('WORKFLOW_ORDER_AUTOCREATE_INVOICE'))
        );

        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/mymodule/temp");
        $this->dirs = array();

        // Config pages. Put here list of php pages
        // stored into mymodule/admin directory, used to setup module.
        $this->config_page_url = array("propalehistory_setup.php@propalehistory");

        // Dependencies
        // List of modules id that must be enabled if this module is enabled
        $this->depends = array('modAbricot');
        // List of modules id to disable if this one is disabled
        $this->requiredby = array();
        // Minimum version of PHP required by module
        $this->phpmin = array(5, 3);
        // Minimum version of Dolibarr required by module
        $this->need_dolibarr_version = array(3, 2);
        $this->langfiles = array("propalehistory@propalehistory"); // langfiles@mymodule
        // Constants
        // List of particular constants to add when module is enabled
        // (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
        // Example:
        $this->const = array(
        	1 => array('PROPALEHISTORY_AUTO_ARCHIVE', 'chaine', 0, $langs->trans('PROPALEHISTORY_AUTO_ARCHIVE_DESC'), 0, 'current', 0),
        	2 => array('PROPALEHISTORY_SHOW_VERSION_PDF', 'chaine', 0, $langs->trans('PROPALEHISTORY_SHOW_VERSION_PDF_DESC'), 0, 'current', 0),
        	3 => array('PROPALEHISTORY_HIDE_VERSION_ON_TABS', 'chaine', 0, $langs->trans('PROPALEHISTORY_HIDE_VERSION_ON_TABS_DESC'), 0, 'current', 0)
        );

        // Array to add new pages in new tabs
        // Example:
        $this->tabs = array(

        );
        // where objecttype can be
        // 'thirdparty'			to add a tab in third party view
        // 'intervention'		to add a tab in intervention view
        // 'order_supplier'		to add a tab in supplier order view
        // 'invoice_supplier'	to add a tab in supplier invoice view
        // 'invoice'			to add a tab in customer invoice view
        // 'order'				to add a tab in customer order view
        // 'product'			to add a tab in product view
        // 'stock'				to add a tab in stock view
        // 'propal'				to add a tab in propal view
        // 'member'				to add a tab in fundation member view
        // 'contract'			to add a tab in contract view
        // 'user'				to add a tab in user view
        // 'group'				to add a tab in group view
        // 'contact'			to add a tab in contact view
        // 'categories_x'		to add a tab in category view
        // (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
        // Dictionnaries
        if (! isset($conf->mymodule->enabled)) {
            $conf->mymodule=new stdClass();
            $conf->mymodule->enabled = 0;
        }
        $this->dictionnaries = array();


        // Boxes
        // Add here list of php file(s) stored in core/boxes that contains class to show a box.
        $this->boxes = array(); // Boxes list
        $r = 0;

        // Permissions
        $this->rights = array(); // Permission array used by this module
        $r = 0;


        $this->menus = array(); // List of menus to add
        $r = 0;


        // Exports
        $r = 1;


    }

    /**
     * Function called when module is enabled.
     * The init function add constants, boxes, permissions and menus
     * (defined in constructor) into Dolibarr database.
     * It also creates data directories
     *
     * 	@param		string	$options	Options when enabling module ('', 'noboxes')
     * 	@return		int					1 if OK, 0 if KO
     */
    public function init($options = '')
    {
        $sql = array();

        $result = $this->loadTables();

		define('INC_FROM_DOLIBARR', true);
		dol_include_once('/propalehistory/config.php');

		dol_include_once('/comm/propal/class/propal.class.php'); // TODO don't think is needed, please remove it
		dol_include_once('/propalehistory/class/propaleHist.class.php');

		$PDOdb=new TPDOdb;
		$o=new TPropaleHist;
		$o->init_db_by_vars($PDOdb);

        return $this->_init($sql, $options);
    }

    /**
     * Function called when module is disabled.
     * Remove from database constants, boxes and permissions from Dolibarr database.
     * Data directories are not deleted
     *
     * 	@param		string	$options	Options when enabling module ('', 'noboxes')
     * 	@return		int					1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();

        return $this->_remove($sql, $options);
    }

    /**
     * Create tables, keys and data required by module
     * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
     * and create data commands must be stored in directory /mymodule/sql/
     * This function is called by this->init
     *
     * 	@return		int		<=0 if KO, >0 if OK
     */
    private function loadTables()
    {
        return $this->_load_tables('/propalehistory/sql/');
    }
}

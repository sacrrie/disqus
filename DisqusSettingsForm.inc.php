<?php

/**
 * @file plugins/generic/disqus/DisqusSettingsForm.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class disqusSettingsForm
 * @ingroup plugins_generic_disqus
 *
 * @brief Form for managers to modify disqus plugin settings
 */

import('lib.pkp.classes.form.Form');

class DisqusSettingsForm extends Form {

	/** @var int */
	var $contextId;

	/** @var object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin DisqusPlugin
	 * @param $contextId int
	 */
	function __construct($plugin, $contextId) {
		$this->contextId = $contextId;
		$this->plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		$this->addCheck(new FormValidator($this, 'disqusForumName', 'required', 'plugins.generic.disqus.manager.settings.disqusForumNameRequired'));

		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'disqusForumName' => $this->plugin->getSetting($this->contextId, 'disqusForumName'),
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('disqusForumName'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->plugin->getName());
		return parent::fetch($request, $template, $display);
	}


/**
 * Save settings.
 *
 * @param mixed …$functionArgs  Any arguments passed by the Form framework
 * @return mixed               Whatever the parent returns (often a boolean)
 */
public function execute(...$functionArgs) {
    // 1. Save your plugin’s own settings
    $this->plugin->updateSetting(
        $this->contextId,
        'disqusForumName',
        trim($this->getData('disqusForumName'), "\"';"),
        'string'
    );

    // 2. Let the parent class do its thing (notifications, cache-busting, etc.)
    return parent::execute(...$functionArgs);
}
}

?>

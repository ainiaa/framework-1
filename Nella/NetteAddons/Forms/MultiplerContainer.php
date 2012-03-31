<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\NetteAddons\Forms;

/**
 * Multipler item container
 *
 * @author    Patrik Votoček
 */
class MultiplerContainer extends Container
{
	const REMOVE_CONTAINER_BUTTON_ID = '__removecontainer';

	/**
	 * @param string
	 * @param bool
	 * @return \Nette\Forms\Controls\SubmitButton
	 */
	public function addRemoveContainerButton($caption, $cleanUpGroups = FALSE)
	{
		$button = $this->addSubmit(self::REMOVE_CONTAINER_BUTTON_ID, $caption)->setValidationScope(FALSE);
		$button->onClick[] = function(\Nette\Forms\Controls\SubmitButton $button) use($cleanUpGroups) {
			$container = $button->getParent();
			$container->getParent()->remove($container, $cleanUpGroups);
		};
		return $button;
	}
}
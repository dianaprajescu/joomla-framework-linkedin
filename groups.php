<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Linkedin
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die();

/**
 * Linkedin API Groups class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Linkedin
 * @since       12.3
 */
class JLinkedinGroups extends JLinkedinObject
{
	/**
	 * Method to get a group.
	 *
	 * @param   JLinkedinOAuth  $oauth   The JLinkedinOAuth object.
	 * @param   string          $id      The unique identifier for a group.
	 * @param   string          $fields  Request fields beyond the default ones.
	 * @param   integer         $start   Starting location within the result set for paginated returns.
	 * @param   integer         $count   The number of results returned.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function getGroup($oauth, $id, $fields = null, $start = 0, $count = 5)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/groups/' . $id;

		$data['format'] = 'json';

		// Check if fields is specified.
		if ($fields)
		{
			$base .= ':' . $fields;
		}

		// Check if start is specified.
		if ($start > 0)
		{
			$data['start'] = $start;
		}

		// Check if count is specified.
		if ($count != 5)
		{
			$data['count'] = $count;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}

	/**
	 * Method to find the groups a member belongs to.
	 *
	 * @param   JLinkedinOAuth  $oauth             The JLinkedinOAuth object.
	 * @param   string          $id                The unique identifier for a user.
	 * @param   string          $fields            Request fields beyond the default ones.
	 * @param   integer         $start             Starting location within the result set for paginated returns.
	 * @param   integer         $count             The number of results returned.
	 * @param   string          $membership_state  The state of the caller’s membership to the specified group.
	 * 											   Values are: non-member, awaiting-confirmation, awaiting-parent-group-confirmation, member, moderator, manager, owner.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function getMemberships($oauth, $id = null, $fields = null, $start = 0, $count = 5, $membership_state = null)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/people/';

		// Check if id is specified.
		if ($id)
		{
			$base .= $id . '/group-memberships';
		}
		else
		{
			$base .= '~/group-memberships';
		}

		$data['format'] = 'json';

		// Check if fields is specified.
		if ($fields)
		{
			$base .= ':' . $fields;
		}

		// Check if start is specified.
		if ($start > 0)
		{
			$data['start'] = $start;
		}

		// Check if count is specified.
		if ($count != 5)
		{
			$data['count'] = $count;
		}

		// Check if membership_state is specified.
		if ($membership_state)
		{
			$data['membership-state'] = $membership_state;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}
}

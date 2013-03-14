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
 * Linkedin API Companies class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Linkedin
 * @since       12.3
 */
class JLinkedinCompanies extends JLinkedinObject
{
	/**
	 * Method to retrieve companies using a company ID, a universal name, or an email domain.
	 *
	 * @param   JLinkedinOAuth  $oauth   The JLinkedinOAuth object.
	 * @param   integer         $id      The unique internal numeric company identifier.
	 * @param   string          $name    The unique string identifier for a company.
	 * @param   string          $domain  Company email domains.
	 * @param   string          $fields  Request fields beyond the default ones.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 * @throws  RuntimeException
	 */
	public function getCompanies($oauth, $id = null, $name = null, $domain = null, $fields = null)
	{
		// At least one value is needed to retrieve data.
		if ($id == null && $name == null && $domain == null)
		{
			// We don't have a valid entry
			throw new RuntimeException('You must specify a company ID, a universal name, or an email domain.');
		}

		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/companies';

		if ($id && $name)
		{
			$base .= '::(' . $id . ',universal-name=' . $name . ')';
		}
		elseif ($id)
		{
			$base .= '/' . $id;
		}
		elseif ($name)
		{
			$base .= '/universal-name=' . $name;
		}

		// Set request parameters.
		$data['format'] = 'json';

		if ($domain)
		{
			$data['email-domain'] = $domain;
		}

		// Check if fields is specified.
		if ($fields)
		{
			$base .= ':' . $fields;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}

	/**
	 * Method to read shares for a particular company .
	 *
	 * @param   JLinkedinOAuth  $oauth  The JLinkedinOAuth object.
	 * @param   string          $id     The unique company identifier.
	 * @param   string          $type   Any valid Company Update Type from the table: https://developer.linkedin.com/reading-company-updates.
	 * @param   integer         $count  Maximum number of updates to return.
	 * @param   integer         $start  The offset by which to start Network Update pagination.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function getUpdates($oauth, $id, $type = null, $count = 0, $start = 0)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/companies/' . $id . '/updates';

		// Set request parameters.
		$data['format'] = 'json';

		// Check if type is specified.
		if ($type)
		{
			$data['event-type'] = $type;
		}

		// Check if count is specified.
		if ($count > 0)
		{
			$data['count'] = $count;
		}

		// Check if start is specified.
		if ($start > 0)
		{
			$data['start'] = $start;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}

	/**
	 * Method to search across company pages.
	 *
	 * @param   JLinkedinOAuth  $oauth     The JLinkedinOAuth object.
	 * @param   string          $fields    Request fields beyond the default ones.
	 * @param   string          $keywords  Members who have all the keywords anywhere in their profile.
	 * @param   boolean         $hq        Matching companies by the headquarters location. When this is set to "true" and a location facet is used,
	 * 									   this restricts returned companies to only those whose headquarters resides in the specified location.
	 * @param   string          $facets    Facet buckets to return, e.g. location.
	 * @param   array           $facet     Array of facet values to search over. Contains values for location, industry, network, company-size,
	 * 									   num-followers-range and fortune, in exactly this order, null must be specified for an element if no value.
	 * @param   integer         $start     Starting location within the result set for paginated returns.
	 * @param   integer         $count     The number of results returned.
	 * @param   string          $sort      Controls the search result order. There are four options: relevance, relationship,
	 * 									   followers and company-size.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function search($oauth, $fields = null, $keywords = null, $hq = false, $facets = null, $facet = null, $start = 0, $count = 0, $sort = null)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/company-search';

		$data['format'] = 'json';

		// Check if fields is specified.
		if ($fields)
		{
			$base .= ':' . $fields;
		}

		// Check if keywords is specified.
		if ($keywords)
		{
			$data['keywords'] = $oauth->safeEncode($keywords);
		}

		// Check if hq is true.
		if ($hq)
		{
			$data['hq-only'] = $hq;
		}

		// Check if facets is specified.
		if ($facets)
		{
			$data['facets'] = $facets;
		}

		// Check if facet is specified.
		if ($facet)
		{
			$data['facet'] = array();
			for ($i = 0; $i < count($facet); $i++)
			{
				if ($facet[$i])
				{
					if ($i == 0)
					{
						$data['facet'][] = 'location,' . $facet[$i];
					}
					if ($i == 1)
					{
						$data['facet'][] = 'industry,' . $facet[$i];
					}
					if ($i == 2)
					{
						$data['facet'][] = 'network,' . $facet[$i];
					}
					if ($i == 3)
					{
						$data['facet'][] = 'company-size,' . $facet[$i];
					}
					if ($i == 4)
					{
						$data['facet'][] = 'num-followers-range,' . $facet[$i];
					}
					if ($i == 5)
					{
						$data['facet'][] = 'fortune,' . $facet[$i];
					}
				}
			}
		}

		// Check if start is specified.
		if ($start > 0)
		{
			$data['start'] = $start;
		}

		// Check if count is specified.
		if ($count > 0)
		{
			$data['count'] = $count;
		}

		// Check if sort is specified.
		if ($sort)
		{
			$data['sort'] = $sort;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}

	/**
	 * Method to get a list of companies the current member is following.
	 *
	 * @param   JLinkedinOAuth  $oauth   The JLinkedinOAuth object.
	 * @param   string          $fields  Request fields beyond the default ones.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function getFollowed($oauth, $fields = null)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/people/~/following/companies';

		$data['format'] = 'json';

		// Check if fields is specified.
		if ($fields)
		{
			$base .= ':' . $fields;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}

	/**
	 * Method to follow a company.
	 *
	 * @param   JLinkedinOAuth  $oauth  The JLinkedinOAuth object.
	 * @param   string          $id     The unique identifier for a company.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function follow($oauth, $id)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the success response code.
		$oauth->setOption('success_code', 201);

		// Set the API base
		$base = '/v1/people/~/following/companies';

		// Build xml.
		$xml = '<company><id>' . $id . '</id></company>';

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		$header['Content-Type'] = 'text/xml';

		// Send the request.
		$response = $oauth->oauthRequest($path, 'POST', $parameters, $xml, $header);

		return $response;
	}

	/**
	 * Method to unfollow a company.
	 *
	 * @param   JLinkedinOAuth  $oauth  The JLinkedinOAuth object.
	 * @param   string          $id     The unique identifier for a company.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function unfollow($oauth, $id)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the success response code.
		$oauth->setOption('success_code', 204);

		// Set the API base
		$base = '/v1/people/~/following/companies/id=' . $id;

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'DELETE', $parameters);

		return $response;
	}

	/**
	 * Method to get a collection of suggested companies for the current user.
	 *
	 * @param   JLinkedinOAuth  $oauth   The JLinkedinOAuth object.
	 * @param   string          $fields  Request fields beyond the default ones.
	 * @param   integer         $start   Starting location within the result set for paginated returns.
	 * @param   integer         $count   The number of results returned.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function getSuggested($oauth, $fields = null, $start = 0, $count = 0)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/people/~/suggestions/to-follow/companies';

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
		if ($count > 0)
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
	 * Method to get a collection of suggested companies for the current user.
	 *
	 * @param   JLinkedinOAuth  $oauth   The JLinkedinOAuth object.
	 * @param   string          $id      The unique identifier for a company.
	 * @param   string          $fields  Request fields beyond the default ones.
	 * @param   integer         $start   Starting location within the result set for paginated returns.
	 * @param   integer         $count   The number of results returned.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   12.3
	 */
	public function getProducts($oauth, $id, $fields = null, $start = 0, $count = 0)
	{
		// Set parameters.
		$parameters = array(
			'oauth_token' => $oauth->getToken('key')
		);

		// Set the API base
		$base = '/v1/companies/' . $id . '/products';

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
		if ($count > 0)
		{
			$data['count'] = $count;
		}

		// Build the request path.
		$path = $this->getOption('api.url') . $base;

		// Send the request.
		$response = $oauth->oauthRequest($path, 'GET', $parameters, $data);
		return json_decode($response->body);
	}
}
